const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const os = require('os');
const path = require('path');

const app = express();
const server = http.createServer(app);
const io = new Server(server, {
  cors: {
    origin: '*',
  }
});

const PORT = process.env.PORT || 3000;

// Serve static files from public directory
app.use(express.static(path.join(__dirname, 'public')));

// Application State
const state = {
  roundName: 'Babak 1',
  status: 'IDLE', // 'IDLE' | 'COUNTDOWN' | 'RUNNING' | 'FINISHED'
  startTimestamp: null,
  countdownValue: null,
  participants: {}, // socketId -> { id, username, status: 'READY'|'RUNNING'|'FINISHED', finishTime: ms, formattedTime: string, rank: number, penalty: number }
  adminSockets: new Set()
};

// Helper: Get local network IPs for easy sharing
function getLocalIPs() {
  const interfaces = os.networkInterfaces();
  const addresses = [];
  for (const name of Object.keys(interfaces)) {
    for (const iface of interfaces[name]) {
      if (iface.family === 'IPv4' && !iface.internal) {
        addresses.push(iface.address);
      }
    }
  }
  return addresses;
}

// Helper: Format milliseconds into mm:ss.mmm
function formatTime(ms) {
  if (ms === null || ms === undefined || isNaN(ms)) return '--:--.---';
  const totalSeconds = ms / 1000;
  const minutes = Math.floor(totalSeconds / 60);
  const seconds = Math.floor(totalSeconds % 60);
  const milliseconds = Math.floor(ms % 1000);

  const mStr = String(minutes).padStart(2, '0');
  const sStr = String(seconds).padStart(2, '0');
  const msStr = String(milliseconds).padStart(3, '0');

  return `${mStr}:${sStr}.${msStr}`;
}

// Recompute Leaderboard Rankings
function updateRankings() {
  const finishedList = Object.values(state.participants)
    .filter(p => p.status === 'FINISHED' && p.finishTime !== null)
    .sort((a, b) => (a.finishTime + (a.penalty || 0)) - (b.finishTime + (b.penalty || 0)));

  finishedList.forEach((p, index) => {
    p.rank = index + 1;
  });

  const notFinishedList = Object.values(state.participants)
    .filter(p => p.status !== 'FINISHED');

  notFinishedList.forEach(p => {
    p.rank = null;
  });
}

// Broadcast current full state to all clients
function broadcastState() {
  updateRankings();
  const payload = {
    roundName: state.roundName,
    status: state.status,
    startTimestamp: state.startTimestamp,
    serverTime: Date.now(),
    countdownValue: state.countdownValue,
    participants: Object.values(state.participants),
    localIPs: getLocalIPs(),
    port: PORT
  };
  io.emit('stateUpdate', payload);
}

let countdownTimer = null;

io.on('connection', (socket) => {
  console.log(`[+] Client connected: ${socket.id}`);

  // Send initial state upon connect
  socket.emit('stateUpdate', {
    roundName: state.roundName,
    status: state.status,
    startTimestamp: state.startTimestamp,
    serverTime: Date.now(),
    countdownValue: state.countdownValue,
    participants: Object.values(state.participants),
    localIPs: getLocalIPs(),
    port: PORT
  });

  // Participant Login (Username only)
  socket.on('userLogin', ({ username }) => {
    const cleanUsername = (username || '').trim();
    if (!cleanUsername) {
      return socket.emit('loginError', 'Nama / Username tidak boleh kosong.');
    }

    // Check if username already exists in another socket
    const existing = Object.values(state.participants).find(
      p => p.username.toLowerCase() === cleanUsername.toLowerCase() && p.id !== socket.id
    );

    if (existing) {
      return socket.emit('loginError', 'Username sudah dipakai oleh peserta lain!');
    }

    state.participants[socket.id] = {
      id: socket.id,
      username: cleanUsername,
      status: state.status === 'RUNNING' ? 'RUNNING' : 'READY',
      finishTime: null,
      formattedTime: null,
      rank: null,
      penalty: 0,
      joinedAt: Date.now()
    };

    socket.emit('loginSuccess', {
      user: state.participants[socket.id],
      isAdmin: false
    });

    broadcastState();
  });

  // Admin Identification
  socket.on('adminLogin', ({ password }) => {
    // Default admin pin/password is admin123
    if (password === 'admin' || password === 'admin123' || password === 'robotika') {
      state.adminSockets.add(socket.id);
      socket.emit('loginSuccess', {
        user: { username: 'Admin (Master)' },
        isAdmin: true
      });
      broadcastState();
    } else {
      socket.emit('loginError', 'Password Admin salah! (Gunakan: admin123)');
    }
  });

  // User Action: Stop Timer
  socket.on('userStopTimer', () => {
    const participant = state.participants[socket.id];
    if (!participant) return;

    if (state.status !== 'RUNNING' || !state.startTimestamp) {
      return socket.emit('actionError', 'Timer belum berjalan atau sudah dihentikan.');
    }

    if (participant.status === 'FINISHED') {
      return socket.emit('actionError', 'Anda sudah menghentikan timer sebelumnya.');
    }

    const now = Date.now();
    const elapsed = Math.max(0, now - state.startTimestamp);

    participant.status = 'FINISHED';
    participant.finishTime = elapsed;
    participant.formattedTime = formatTime(elapsed);

    updateRankings();

    // Notify user specifically
    socket.emit('timerStopped', {
      finishTime: elapsed,
      formattedTime: participant.formattedTime,
      rank: participant.rank
    });

    // Notify admin & all
    io.emit('participantFinished', {
      id: participant.id,
      username: participant.username,
      finishTime: elapsed,
      formattedTime: participant.formattedTime,
      rank: participant.rank
    });

    broadcastState();
  });

  // Admin Action: Start Countdown & Timer
  socket.on('adminStartTimer', ({ withCountdown = true } = {}) => {
    if (countdownTimer) {
      clearInterval(countdownTimer);
      countdownTimer = null;
    }

    // Reset participant finish status for this run
    Object.values(state.participants).forEach(p => {
      p.status = 'READY';
      p.finishTime = null;
      p.formattedTime = null;
      p.rank = null;
    });

    if (withCountdown) {
      state.status = 'COUNTDOWN';
      let count = 3;
      state.countdownValue = count;
      broadcastState();
      io.emit('soundEvent', { type: 'beep' });

      countdownTimer = setInterval(() => {
        count -= 1;
        if (count > 0) {
          state.countdownValue = count;
          broadcastState();
          io.emit('soundEvent', { type: 'beep' });
        } else if (count === 0) {
          state.countdownValue = 'GO!';
          broadcastState();
          io.emit('soundEvent', { type: 'start' });
        } else {
          clearInterval(countdownTimer);
          countdownTimer = null;
          state.status = 'RUNNING';
          state.countdownValue = null;
          state.startTimestamp = Date.now();

          Object.values(state.participants).forEach(p => {
            p.status = 'RUNNING';
          });

          broadcastState();
        }
      }, 1000);
    } else {
      state.status = 'RUNNING';
      state.countdownValue = null;
      state.startTimestamp = Date.now();
      Object.values(state.participants).forEach(p => {
        p.status = 'RUNNING';
      });
      io.emit('soundEvent', { type: 'start' });
      broadcastState();
    }
  });

  // Admin Action: Stop All
  socket.on('adminStopTimer', () => {
    if (countdownTimer) {
      clearInterval(countdownTimer);
      countdownTimer = null;
    }
    state.status = 'FINISHED';
    state.countdownValue = null;

    // For any participant still RUNNING, lock in current elapsed time
    const now = Date.now();
    if (state.startTimestamp) {
      const elapsed = Math.max(0, now - state.startTimestamp);
      Object.values(state.participants).forEach(p => {
        if (p.status === 'RUNNING') {
          p.status = 'FINISHED';
          p.finishTime = elapsed;
          p.formattedTime = formatTime(elapsed);
        }
      });
    }
    broadcastState();
  });

  // Admin Action: Reset Timer / Round
  socket.on('adminResetTimer', () => {
    if (countdownTimer) {
      clearInterval(countdownTimer);
      countdownTimer = null;
    }
    state.status = 'IDLE';
    state.startTimestamp = null;
    state.countdownValue = null;

    Object.values(state.participants).forEach(p => {
      p.status = 'READY';
      p.finishTime = null;
      p.formattedTime = null;
      p.rank = null;
    });

    broadcastState();
  });

  // Admin Action: Change Round Name
  socket.on('adminSetRoundName', ({ roundName }) => {
    if (roundName && typeof roundName === 'string') {
      state.roundName = roundName.trim();
      broadcastState();
    }
  });

  // Admin Action: Reset Specific Participant
  socket.on('adminResetParticipant', ({ participantId }) => {
    const p = state.participants[participantId];
    if (p) {
      p.status = state.status === 'RUNNING' ? 'RUNNING' : 'READY';
      p.finishTime = null;
      p.formattedTime = null;
      p.rank = null;
      broadcastState();
    }
  });

  // Admin Action: Add Penalty / Adjustment (in seconds)
  socket.on('adminAdjustPenalty', ({ participantId, penaltyDeltaSeconds }) => {
    const p = state.participants[participantId];
    if (p) {
      p.penalty = (p.penalty || 0) + (penaltyDeltaSeconds * 1000);
      broadcastState();
    }
  });

  // Admin Action: Kick Participant
  socket.on('adminKickParticipant', ({ participantId }) => {
    if (state.participants[participantId]) {
      io.to(participantId).emit('kickedByAdmin');
      delete state.participants[participantId];
      broadcastState();
    }
  });

  // Disconnect handler
  socket.on('disconnect', () => {
    console.log(`[-] Client disconnected: ${socket.id}`);
    state.adminSockets.delete(socket.id);
    if (state.participants[socket.id]) {
      delete state.participants[socket.id];
      broadcastState();
    }
  });
});

server.listen(PORT, '0.0.0.0', () => {
  console.log(`\n======================================================`);
  console.log(`🚀 ROBOTICS RACE TIMER SERVER IS RUNNING ON PORT ${PORT}`);
  console.log(`🌐 Local Access:    http://localhost:${PORT}`);
  const ips = getLocalIPs();
  if (ips.length > 0) {
    ips.forEach(ip => {
      console.log(`📱 LAN / Mobile:   http://${ip}:${PORT}`);
    });
  }
  console.log(`🔑 Admin Default Password: admin123`);
  console.log(`======================================================\n`);
});
