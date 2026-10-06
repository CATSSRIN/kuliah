/**
 * ROBOTICS RACE TIMER - CLIENT LOGIC
 * Real-time synchronization via Socket.IO & Web Audio API Sound Synthesizer
 */

// ========================================================
// 1. STATE & GLOBALS
// ========================================================
const socket = io();

let currentUser = null;
let isAdmin = false;
let serverState = {
  roundName: 'Babak 1',
  status: 'IDLE',
  startTimestamp: null,
  countdownValue: null,
  participants: [],
  localIPs: [],
  port: 3000
};

let soundEnabled = true;
let audioCtx = null;
let animationFrameId = null;

// ========================================================
// 2. AUDIO SYNTHESIZER (Web Audio API)
// ========================================================
function initAudio() {
  if (!audioCtx) {
    const AudioContext = window.AudioContext || window.webkitAudioContext;
    if (AudioContext) {
      audioCtx = new AudioContext();
    }
  }
  if (audioCtx && audioCtx.state === 'suspended') {
    audioCtx.resume();
  }
}

function playTone(freq, type = 'sine', duration = 0.15, gainVal = 0.2) {
  if (!soundEnabled) return;
  try {
    initAudio();
    if (!audioCtx) return;

    const osc = audioCtx.createOscillator();
    const gain = audioCtx.createGain();

    osc.type = type;
    osc.frequency.setValueAtTime(freq, audioCtx.currentTime);

    gain.gain.setValueAtTime(gainVal, audioCtx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + duration);

    osc.connect(gain);
    gain.connect(audioCtx.destination);

    osc.start();
    osc.stop(audioCtx.currentTime + duration);
  } catch (err) {
    console.warn('Audio play error:', err);
  }
}

function playCountdownBeep() {
  playTone(523.25, 'triangle', 0.2, 0.25); // Note C5
}

function playStartBuzzer() {
  playTone(1046.50, 'sawtooth', 0.5, 0.35); // Note C6 (high energetic)
}

function playStopSnap() {
  playTone(880, 'square', 0.1, 0.2);
  setTimeout(() => playTone(1320, 'sine', 0.2, 0.2), 50);
}

function playFinishChime() {
  playTone(659.25, 'sine', 0.25, 0.2); // E5
  setTimeout(() => playTone(880, 'sine', 0.35, 0.25), 100); // A5
}

function toggleSound() {
  soundEnabled = !soundEnabled;
  const btn = document.getElementById('sound-toggle-btn');
  if (btn) {
    btn.innerHTML = soundEnabled 
      ? '<i class="fa-solid fa-volume-high"></i>' 
      : '<i class="fa-solid fa-volume-xmark" style="color:#f87171"></i>';
  }
  showToast(soundEnabled ? 'Suara diaktifkan' : 'Suara dinonaktifkan', 'info');
}

// ========================================================
// 3. UTILITY FUNCTIONS
// ========================================================
function formatTime(ms) {
  if (ms === null || ms === undefined || isNaN(ms) || ms < 0) return '00:00.000';
  const totalSeconds = ms / 1000;
  const minutes = Math.floor(totalSeconds / 60);
  const seconds = Math.floor(totalSeconds % 60);
  const milliseconds = Math.floor(ms % 1000);

  const mStr = String(minutes).padStart(2, '0');
  const sStr = String(seconds).padStart(2, '0');
  const msStr = String(milliseconds).padStart(3, '0');

  return `${mStr}:${sStr}.${msStr}`;
}

function showToast(message, type = 'info') {
  const container = document.getElementById('toast-container');
  if (!container) return;

  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  
  let icon = 'fa-info-circle';
  if (type === 'success') icon = 'fa-circle-check';
  if (type === 'error') icon = 'fa-triangle-exclamation';

  toast.innerHTML = `
    <i class="fa-solid ${icon}"></i>
    <span>${message}</span>
  `;

  container.appendChild(toast);

  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(50px)';
    setTimeout(() => toast.remove(), 300);
  }, 3500);
}

// ========================================================
// 4. AUTH & TAB SWITCHING
// ========================================================
function switchAuthTab(tab) {
  const userBtn = document.getElementById('tab-btn-user');
  const adminBtn = document.getElementById('tab-btn-admin');
  const userForm = document.getElementById('user-login-form');
  const adminForm = document.getElementById('admin-login-form');

  if (tab === 'user') {
    userBtn.classList.add('active');
    adminBtn.classList.remove('active');
    userForm.classList.add('active');
    adminForm.classList.remove('active');
    document.getElementById('input-username').focus();
  } else {
    adminBtn.classList.add('active');
    userBtn.classList.remove('active');
    adminForm.classList.add('active');
    userForm.classList.remove('active');
    document.getElementById('input-admin-password').focus();
  }
}

function handleUserLogin(e) {
  e.preventDefault();
  initAudio();
  const usernameInput = document.getElementById('input-username');
  const username = (usernameInput.value || '').trim();

  if (!username) {
    showToast('Silakan masukkan nama peserta / tim', 'error');
    return;
  }

  socket.emit('userLogin', { username });
}

function handleAdminLogin(e) {
  e.preventDefault();
  initAudio();
  const passInput = document.getElementById('input-admin-password');
  const password = passInput.value;

  if (!password) {
    showToast('Masukkan password admin', 'error');
    return;
  }

  socket.emit('adminLogin', { password });
}

function showView(viewId) {
  document.querySelectorAll('.view-section').forEach(el => {
    el.classList.remove('active');
    el.classList.add('hidden');
  });

  const target = document.getElementById(viewId);
  if (target) {
    target.classList.remove('hidden');
    target.classList.add('active');
  }
}

function logout() {
  if (confirm('Apakah Anda yakin ingin keluar?')) {
    sessionStorage.removeItem('race_auth');
    window.location.reload();
  }
}

// ========================================================
// 5. USER ACTIONS & STOPWATCH INTERACTION
// ========================================================
function handleUserStop() {
  initAudio();
  if (!currentUser) return;
  const stopBtn = document.getElementById('user-stop-btn');
  if (stopBtn.disabled) return;

  // Haptic feedback for mobile devices
  if (navigator.vibrate) {
    navigator.vibrate([100]);
  }

  playStopSnap();
  socket.emit('userStopTimer');
  stopBtn.disabled = true;
}

// Spacebar Keybind for Participants
window.addEventListener('keydown', (e) => {
  if (e.code === 'Space' && !isAdmin && currentUser) {
    // Only trigger if not focused in an input
    if (['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) return;
    
    e.preventDefault();
    const stopBtn = document.getElementById('user-stop-btn');
    if (stopBtn && !stopBtn.disabled) {
      handleUserStop();
    }
  }
});

// ========================================================
// 6. ADMIN CONTROLS
// ========================================================
function adminStartWithCountdown() {
  initAudio();
  socket.emit('adminStartTimer', { withCountdown: true });
}

function adminStartDirect() {
  initAudio();
  socket.emit('adminStartTimer', { withCountdown: false });
}

function adminStopAll() {
  socket.emit('adminStopTimer');
}

function adminResetRound() {
  if (confirm('Reset ronde dan semua catatan waktu peserta?')) {
    socket.emit('adminResetTimer');
  }
}

function handleUpdateRoundName(newName) {
  if (newName && newName.trim()) {
    socket.emit('adminSetRoundName', { roundName: newName.trim() });
    showToast('Nama babak diperbarui', 'success');
  }
}

function adminResetParticipant(participantId) {
  socket.emit('adminResetParticipant', { participantId });
}

function adminAdjustPenalty(participantId, deltaSeconds) {
  socket.emit('adminAdjustPenalty', { participantId, penaltyDeltaSeconds: deltaSeconds });
}

function adminKickParticipant(participantId, username) {
  if (confirm(`Keluarkan ${username} dari sesi lomba?`)) {
    socket.emit('adminKickParticipant', { participantId });
  }
}

// ========================================================
// 7. REAL-TIME LOOP & RENDERING
// ========================================================
function startAnimationLoop() {
  if (animationFrameId) cancelAnimationFrame(animationFrameId);

  function loop() {
    renderLiveTimers();
    animationFrameId = requestAnimationFrame(loop);
  }

  animationFrameId = requestAnimationFrame(loop);
}

function renderLiveTimers() {
  const isRunning = serverState.status === 'RUNNING' && serverState.startTimestamp;
  const elapsed = isRunning ? Math.max(0, Date.now() - serverState.startTimestamp) : 0;
  const formattedRunning = formatTime(elapsed);

  // Admin Master Timer Display
  const adminTimerEl = document.getElementById('admin-timer-display');
  if (adminTimerEl && isAdmin) {
    if (isRunning) {
      adminTimerEl.innerText = formattedRunning;
    } else if (serverState.status === 'IDLE') {
      adminTimerEl.innerText = '00:00.000';
    }
  }

  // User Timer Display
  const userTimerEl = document.getElementById('user-timer-display');
  if (userTimerEl && !isAdmin && currentUser) {
    const myData = serverState.participants.find(p => p.id === socket.id);
    if (myData && myData.status === 'FINISHED' && myData.finishTime !== null) {
      userTimerEl.innerText = formatTime(myData.finishTime);
    } else if (isRunning) {
      userTimerEl.innerText = formattedRunning;
    } else if (serverState.status === 'IDLE') {
      userTimerEl.innerText = '00:00.000';
    }
  }
}

function renderState() {
  // Update Round Titles
  const userRoundEl = document.getElementById('user-round-title');
  if (userRoundEl) userRoundEl.innerText = serverState.roundName || 'Babak 1';

  const adminRoundInput = document.getElementById('admin-round-input');
  if (adminRoundInput && document.activeElement !== adminRoundInput) {
    adminRoundInput.value = serverState.roundName || 'Babak 1';
  }

  // Handle Countdown Overlay
  const cdOverlay = document.getElementById('countdown-overlay');
  const cdNumber = document.getElementById('countdown-number');
  if (serverState.status === 'COUNTDOWN' && serverState.countdownValue !== null) {
    cdOverlay.classList.remove('hidden');
    cdNumber.innerText = serverState.countdownValue;
  } else {
    cdOverlay.classList.add('hidden');
  }

  // Render Admin View
  if (isAdmin) {
    renderAdminDashboard();
  }

  // Render User View
  if (!isAdmin && currentUser) {
    renderUserStage();
  }
}

function renderUserStage() {
  const myData = serverState.participants.find(p => p.id === socket.id);
  const statusPill = document.getElementById('user-status-pill');
  const statusText = document.getElementById('user-status-text');
  const stopBtn = document.getElementById('user-stop-btn');
  const helperMsg = document.getElementById('user-helper-msg');
  const resultCard = document.getElementById('user-result-card');
  const resultTimeEl = document.getElementById('user-result-time');
  const rankBadge = document.getElementById('user-rank-badge');

  const isFinished = myData && myData.status === 'FINISHED';

  if (isFinished) {
    statusPill.className = 'status-pill finished';
    statusPill.innerHTML = '<i class="fa-solid fa-flag-checkered"></i> <span>FINISH! WAKTU TERKUNCI</span>';
    stopBtn.disabled = true;
    helperMsg.innerText = 'Waktu Anda berhasil dicatat dan dikirim ke dewan juri/admin!';
    resultCard.classList.remove('hidden');
    resultTimeEl.innerText = formatTime(myData.finishTime + (myData.penalty || 0));

    if (myData.rank) {
      rankBadge.classList.remove('hidden');
      rankBadge.innerText = `Peringkat #${myData.rank}`;
    } else {
      rankBadge.classList.add('hidden');
    }
  } else if (serverState.status === 'RUNNING') {
    statusPill.className = 'status-pill running';
    statusPill.innerHTML = '<i class="fa-solid fa-bolt"></i> <span>LOMBA BERJALAN! KLIK STOP SAAT FINISH</span>';
    stopBtn.disabled = false;
    helperMsg.innerText = 'Fokus pada robot / lintasan. Tekan tombol STOP atau tombol SPASI saat robot menyentuh garis finish!';
    resultCard.classList.add('hidden');
    rankBadge.classList.add('hidden');
  } else if (serverState.status === 'COUNTDOWN') {
    statusPill.className = 'status-pill countdown';
    statusPill.innerHTML = '<i class="fa-solid fa-hourglass-half"></i> <span>COUNTDOWN... BERSIAP!</span>';
    stopBtn.disabled = true;
    helperMsg.innerText = 'Hitungan mundur sedang berlangsung...';
    resultCard.classList.add('hidden');
    rankBadge.classList.add('hidden');
  } else {
    statusPill.className = 'status-pill idle';
    statusPill.innerHTML = '<i class="fa-solid fa-circle-pause"></i> <span>Menunggu Admin Memulai Timer...</span>';
    stopBtn.disabled = true;
    helperMsg.innerText = 'Bersiap di lintasan. Tombol STOP akan aktif saat admin memulai lomba!';
    resultCard.classList.add('hidden');
    rankBadge.classList.add('hidden');
  }
}

function renderAdminDashboard() {
  // Update status badge
  const statusBadge = document.getElementById('admin-status-badge');
  if (statusBadge) {
    statusBadge.className = `badge-status ${serverState.status.toLowerCase()}`;
    statusBadge.innerText = serverState.status;
  }

  // Update control buttons state
  const btnStartCd = document.getElementById('btn-admin-start-cd');
  const btnStartDirect = document.getElementById('btn-admin-start-direct');
  const btnStop = document.getElementById('btn-admin-stop');

  if (serverState.status === 'RUNNING') {
    btnStartCd.disabled = true;
    btnStartDirect.disabled = true;
    btnStop.disabled = false;
  } else if (serverState.status === 'COUNTDOWN') {
    btnStartCd.disabled = true;
    btnStartDirect.disabled = true;
    btnStop.disabled = false;
  } else {
    btnStartCd.disabled = false;
    btnStartDirect.disabled = false;
    btnStop.disabled = true;
  }

  // Update Stats Counters
  const participants = serverState.participants || [];
  const totalUsers = participants.length;
  const runningUsers = participants.filter(p => p.status === 'RUNNING').length;
  const finishedUsers = participants.filter(p => p.status === 'FINISHED').length;

  document.getElementById('stat-total-users').innerText = totalUsers;
  document.getElementById('stat-running-users').innerText = runningUsers;
  document.getElementById('stat-finished-users').innerText = finishedUsers;

  // Best Time (#1 finished)
  const sortedFinished = participants
    .filter(p => p.status === 'FINISHED' && p.finishTime !== null)
    .sort((a, b) => (a.finishTime + (a.penalty || 0)) - (b.finishTime + (b.penalty || 0)));

  const bestTimeEl = document.getElementById('stat-best-time');
  if (sortedFinished.length > 0) {
    const bestTotal = sortedFinished[0].finishTime + (sortedFinished[0].penalty || 0);
    bestTimeEl.innerText = formatTime(bestTotal);
  } else {
    bestTimeEl.innerText = '--:--.---';
  }

  // Update Local IPs
  const ipContainer = document.getElementById('admin-ip-list');
  if (ipContainer && serverState.localIPs) {
    const port = serverState.port || 3000;
    const ipBadges = [
      `<span class="ip-badge">http://localhost:${port}</span>`,
      ...serverState.localIPs.map(ip => `<span class="ip-badge" title="Klik untuk salin" onclick="navigator.clipboard.writeText('http://${ip}:${port}'); showToast('URL disalin!', 'success')">http://${ip}:${port}</span>`)
    ];
    ipContainer.innerHTML = ipBadges.join('');
  }

  // Render Leaderboard Table
  renderLeaderboardTable(participants, sortedFinished);
}

function renderLeaderboardTable(participants, sortedFinished) {
  const tbody = document.getElementById('leaderboard-tbody');
  if (!tbody) return;

  if (participants.length === 0) {
    tbody.innerHTML = `
      <tr class="empty-row">
        <td colspan="8">
          <div class="empty-state">
            <i class="fa-solid fa-user-clock"></i>
            <p>Belum ada peserta yang bergabung.</p>
            <small>Minta peserta membuka website ini dan memasukkan nama tim mereka.</small>
          </div>
        </td>
      </tr>
    `;
    return;
  }

  // Sort: Finished users by total time (asc), then Running, then Ready
  const allSorted = [...participants].sort((a, b) => {
    if (a.status === 'FINISHED' && b.status === 'FINISHED') {
      return (a.finishTime + (a.penalty || 0)) - (b.finishTime + (b.penalty || 0));
    }
    if (a.status === 'FINISHED') return -1;
    if (b.status === 'FINISHED') return 1;
    if (a.status === 'RUNNING' && b.status !== 'RUNNING') return -1;
    if (b.status === 'RUNNING' && a.status !== 'RUNNING') return 1;
    return (a.joinedAt || 0) - (b.joinedAt || 0);
  });

  const bestTotalMs = sortedFinished.length > 0 
    ? (sortedFinished[0].finishTime + (sortedFinished[0].penalty || 0))
    : null;

  tbody.innerHTML = allSorted.map((p, idx) => {
    let rankBadge = `<span class="rank-badge rank-other">${idx + 1}</span>`;
    if (p.status === 'FINISHED' && p.rank) {
      if (p.rank === 1) rankBadge = `<span class="rank-badge rank-1" title="Juara 1">🥇 1</span>`;
      else if (p.rank === 2) rankBadge = `<span class="rank-badge rank-2" title="Juara 2">🥈 2</span>`;
      else if (p.rank === 3) rankBadge = `<span class="rank-badge rank-3" title="Juara 3">🥉 3</span>`;
      else rankBadge = `<span class="rank-badge rank-other">#${p.rank}</span>`;
    }

    let statusBadge = `<span class="user-badge ready"><i class="fa-regular fa-clock"></i> Standby</span>`;
    if (p.status === 'RUNNING') {
      statusBadge = `<span class="user-badge running"><i class="fa-solid fa-person-running"></i> Running</span>`;
    } else if (p.status === 'FINISHED') {
      statusBadge = `<span class="user-badge finished"><i class="fa-solid fa-flag-checkered"></i> Finish</span>`;
    }

    const finishFormatted = p.finishTime !== null ? formatTime(p.finishTime) : '--:--.---';
    const totalMs = p.finishTime !== null ? (p.finishTime + (p.penalty || 0)) : null;
    const totalFormatted = totalMs !== null ? formatTime(totalMs) : '--:--.---';

    let deltaStr = '-';
    if (p.status === 'FINISHED' && totalMs !== null && bestTotalMs !== null) {
      const diff = totalMs - bestTotalMs;
      if (diff === 0) {
        deltaStr = '<span style="color:#34d399;font-weight:bold">LEADER</span>';
      } else {
        deltaStr = `+${(diff / 1000).toFixed(3)}s`;
      }
    }

    const penaltySec = (p.penalty || 0) / 1000;
    const penaltyDisplay = penaltySec > 0 ? `+${penaltySec}s` : (penaltySec < 0 ? `${penaltySec}s` : '0s');

    return `
      <tr class="${p.rank === 1 ? 'row-winner' : ''}">
        <td>${rankBadge}</td>
        <td><strong>${p.username}</strong></td>
        <td>${statusBadge}</td>
        <td class="time-cell ${p.status === 'FINISHED' ? 'finished-time' : ''}">${finishFormatted}</td>
        <td>
          <div class="penalty-control">
            <button class="btn-mini" title="Kurangi Penalti 1s" onclick="adminAdjustPenalty('${p.id}', -1)">-</button>
            <span class="penalty-badge">${penaltyDisplay}</span>
            <button class="btn-mini" title="Tambah Penalti 1s" onclick="adminAdjustPenalty('${p.id}', 1)">+</button>
          </div>
        </td>
        <td class="time-cell ${p.rank === 1 ? 'best-highlight' : ''}">${totalFormatted}</td>
        <td class="delta-cell">${deltaStr}</td>
        <td class="text-right">
          <div style="display:flex;gap:4px;justify-content:flex-end">
            <button class="btn btn-icon btn-sm" title="Reset Waktu Peserta Ini" onclick="adminResetParticipant('${p.id}')">
              <i class="fa-solid fa-rotate-left"></i>
            </button>
            <button class="btn btn-icon btn-sm btn-danger-ghost" title="Keluarkan Peserta" onclick="adminKickParticipant('${p.id}', '${p.username}')">
              <i class="fa-solid fa-trash-can"></i>
            </button>
          </div>
        </td>
      </tr>
    `;
  }).join('');
}

// ========================================================
// 8. EXPORT CSV & PRINT
// ========================================================
function exportToCSV() {
  const participants = serverState.participants || [];
  if (participants.length === 0) {
    showToast('Tidak ada data peserta untuk di-export', 'error');
    return;
  }

  const sorted = [...participants].sort((a, b) => {
    if (a.status === 'FINISHED' && b.status === 'FINISHED') {
      return (a.finishTime + (a.penalty || 0)) - (b.finishTime + (b.penalty || 0));
    }
    if (a.status === 'FINISHED') return -1;
    if (b.status === 'FINISHED') return 1;
    return 0;
  });

  let csvContent = 'Peringkat,Nama Tim / Peserta,Status,Waktu Finish,Penalti (detik),Waktu Total (ms),Waktu Total (Format)\n';

  sorted.forEach((p, idx) => {
    const rank = p.rank || (idx + 1);
    const finishStr = p.finishTime !== null ? formatTime(p.finishTime) : '-';
    const penaltySec = (p.penalty || 0) / 1000;
    const totalMs = p.finishTime !== null ? (p.finishTime + (p.penalty || 0)) : '-';
    const totalStr = p.finishTime !== null ? formatTime(p.finishTime + (p.penalty || 0)) : '-';

    csvContent += `"${rank}","${p.username}","${p.status}","${finishStr}","${penaltySec}","${totalMs}","${totalStr}"\n`;
  });

  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.setAttribute('href', url);
  link.setAttribute('download', `Hasil_Lomba_${(serverState.roundName || 'Babak').replace(/\s+/g, '_')}_${Date.now()}.csv`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  showToast('Hasil berhasil di-export ke CSV!', 'success');
}

function printLeaderboard() {
  window.print();
}

// ========================================================
// 9. SOCKET EVENT LISTENERS
// ========================================================
socket.on('connect', () => {
  console.log('[+] Connected to Socket.IO Server');
  const userStatus = document.getElementById('connection-status-user');
  const adminStatus = document.getElementById('connection-status-admin');
  if (userStatus) userStatus.className = 'status-indicator online';
  if (adminStatus) adminStatus.className = 'status-indicator online';
});

socket.on('disconnect', () => {
  console.warn('[-] Disconnected from Server');
  const userStatus = document.getElementById('connection-status-user');
  const adminStatus = document.getElementById('connection-status-admin');
  if (userStatus) userStatus.className = 'status-indicator offline';
  if (adminStatus) adminStatus.className = 'status-indicator offline';
});

socket.on('loginSuccess', ({ user, isAdmin: isUserAdmin }) => {
  currentUser = user;
  isAdmin = isUserAdmin;

  if (isAdmin) {
    showView('admin-view');
    showToast('Selamat datang di Dashboard Admin Juri!', 'success');
  } else {
    showView('user-view');
    document.getElementById('user-display-name').innerText = user.username;
    showToast(`Berhasil masuk sebagai ${user.username}`, 'success');
  }

  renderState();
});

socket.on('loginError', (msg) => {
  showToast(msg, 'error');
});

socket.on('actionError', (msg) => {
  showToast(msg, 'error');
});

socket.on('stateUpdate', (newState) => {
  serverState = newState;
  renderState();
});

socket.on('soundEvent', ({ type }) => {
  if (type === 'beep') {
    playCountdownBeep();
  } else if (type === 'start') {
    playStartBuzzer();
  }
});

socket.on('participantFinished', ({ username, formattedTime, rank }) => {
  if (isAdmin) {
    playFinishChime();
    showToast(`🏁 ${username} FINISH: ${formattedTime} (Rank #${rank || '-'})`, 'info');
  }
});

socket.on('timerStopped', ({ formattedTime, rank }) => {
  showToast(`Waktu Anda: ${formattedTime} (Peringkat #${rank || '1'})`, 'success');
  playFinishChime();
});

socket.on('kickedByAdmin', () => {
  alert('Anda telah dikeluarkan dari sesi oleh Admin.');
  window.location.reload();
});

// Initialize live requestAnimationFrame loop on startup
startAnimationLoop();
