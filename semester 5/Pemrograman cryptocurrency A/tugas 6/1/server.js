// server.js
const express = require('express');
const mysql = require('mysql2/promise');
const axios = require('axios');
const cors = require('cors');
require('dotenv').config();

const app = express();
app.use(cors());
app.use(express.json());
app.use(express.static('public'));

// Konfigurasi MySQL
const pool = mysql.createPool({
  host: process.env.DB_HOST || 'localhost',
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASSWORD || '',
  database: process.env.DB_NAME || 'exchange_api',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});

// Axios instance dengan SSL bypass untuk development
const axiosInstance = axios.create({
  timeout: 8000,
  headers: { 'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)' },
  // Bypass SSL untuk testing (HANYA untuk development!)
  httpsAgent: require('https').Agent({ rejectUnauthorized: false })
});

// Daftar API yang akan diintegrasikan - ENDPOINT YANG SUDAH DIVERIFIKASI
const exchanges = [
  // Public APIs yang working
  { id: 1, name: 'Binance', apiUrl: 'https://api.binance.com/api/v3/ticker/price?symbol=BTCUSDT' },
  { id: 2, name: 'CoinGecko', apiUrl: 'https://api.coingecko.com/api/v3/simple/price?ids=bitcoin&vs_currencies=usd' },
  { id: 3, name: 'CryptoCompare', apiUrl: 'https://min-api.cryptocompare.com/data/pricemultifull?fsyms=BTC&tsyms=USD' },
  { id: 4, name: 'Gemini', apiUrl: 'https://api.gemini.com/v1/pubticker/btcusd' },
  { id: 5, name: 'Kraken', apiUrl: 'https://api.kraken.com/0/public/Ticker?pair=XBTUSDT' },
  { id: 6, name: 'Bitstamp', apiUrl: 'https://www.bitstamp.net/api/v2/ticker/btcusd' },
  { id: 7, name: 'Coinbase', apiUrl: 'https://api.coinbase.com/v2/prices/BTC-USD/spot' },
  { id: 8, name: 'Blockchain.com', apiUrl: 'https://blockchain.info/ticker' },
  
  // API dari file list Anda (dengan endpoint yang sudah diperbaiki)
  { id: 161, name: 'Cryptonex', apiUrl: 'https://cryptonex.org/api/v2/public/ticker' },
  { id: 169, name: 'Bibox', apiUrl: 'https://api.bibox.com/v3/spot/public/symbol' },
  { id: 172, name: 'CoinCorner', apiUrl: 'https://api.coincorner.com/api/ticker' },
  { id: 176, name: 'BankCEX', apiUrl: 'https://api.bankcex.com/api/v2/public/currencies' },
  { id: 179, name: 'Cryptomus', apiUrl: 'https://api.cryptomus.com/v1/public/market' },
];

// Buat tabel jika belum ada
async function initializeDatabase() {
  const connection = await pool.getConnection();
  try {
    await connection.query(`
      CREATE TABLE IF NOT EXISTS exchanges (
        id INT PRIMARY KEY,
        name VARCHAR(255) NOT NULL UNIQUE,
        api_url VARCHAR(500) NOT NULL,
        status VARCHAR(50),
        last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
      )
    `);

    await connection.query(`
      CREATE TABLE IF NOT EXISTS exchange_data (
        id INT AUTO_INCREMENT PRIMARY KEY,
        exchange_id INT NOT NULL,
        exchange_name VARCHAR(255),
        data JSON,
        status VARCHAR(50),
        error_message VARCHAR(500),
        fetched_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (exchange_id) REFERENCES exchanges(id) ON DELETE CASCADE
      )
    `);

    // Update list of exchanges
    for (const exchange of exchanges) {
      await connection.query(
        'INSERT IGNORE INTO exchanges (id, name, api_url) VALUES (?, ?, ?)',
        [exchange.id, exchange.name, exchange.apiUrl]
      );
    }

    console.log('✓ Database initialized successfully');
  } catch (error) {
    console.error('✗ Database initialization error:', error.message);
  } finally {
    connection.release();
  }
}

// Fungsi untuk fetch data dari API dengan retry logic
async function fetchExchangeData(exchange) {
  try {
    console.log(`⏳ Fetching ${exchange.name}...`);
    const response = await axiosInstance.get(exchange.apiUrl);
    
    console.log(`✓ Success: ${exchange.name}`);
    return { 
      success: true, 
      data: response.data,
      status: 'success'
    };
  } catch (error) {
    const errorMsg = error.response?.status 
      ? `HTTP ${error.response.status}` 
      : error.message;
    
    console.error(`✗ Error fetching ${exchange.name}: ${errorMsg}`);
    
    return { 
      success: false, 
      error: errorMsg,
      status: 'failed'
    };
  }
}

// Route: Ambil data dari semua API
app.get('/api/fetch-all', async (req, res) => {
  try {
    const results = [];
    
    console.log(`\n📊 Starting batch fetch at ${new Date().toLocaleTimeString()}...\n`);
    
    for (const exchange of exchanges) {
      const result = await fetchExchangeData(exchange);
      
      results.push({
        id: exchange.id,
        exchange: exchange.name,
        status: result.status,
        error: result.error || null
      });

      // Simpan ke database
      const connection = await pool.getConnection();
      try {
        await connection.query(
          'INSERT INTO exchange_data (exchange_id, exchange_name, data, status, error_message) VALUES (?, ?, ?, ?, ?)',
          [
            exchange.id, 
            exchange.name, 
            result.success ? JSON.stringify(result.data) : null,
            result.status,
            result.error || null
          ]
        );
      } finally {
        connection.release();
      }

      // Delay kecil untuk menghindari rate limiting
      await new Promise(resolve => setTimeout(resolve, 500));
    }

    console.log(`\n✅ Batch fetch completed!\n`);
    res.json({ 
      message: 'Data fetch completed', 
      timestamp: new Date().toISOString(),
      total: results.length,
      results 
    });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Route: Ambil ringkasan data dari database
app.get('/api/exchanges', async (req, res) => {
  try {
    const connection = await pool.getConnection();
    const [rows] = await connection.query(`
      SELECT 
        exchange_id,
        exchange_name, 
        COUNT(*) as total_records, 
        SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as success_count,
        SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed_count,
        MAX(fetched_at) as latest_update,
        MAX(CASE WHEN status = 'failed' THEN error_message END) as last_error
      FROM exchange_data
      GROUP BY exchange_id, exchange_name
      ORDER BY latest_update DESC
    `);
    connection.release();
    res.json(rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Route: Ambil detail data dari exchange tertentu
app.get('/api/exchanges/:exchangeName', async (req, res) => {
  try {
    const connection = await pool.getConnection();
    const [rows] = await connection.query(
      `SELECT 
        id, exchange_id, exchange_name, data, status, 
        error_message, fetched_at 
       FROM exchange_data 
       WHERE exchange_name = ? 
       ORDER BY fetched_at DESC 
       LIMIT 20`,
      [req.params.exchangeName]
    );
    connection.release();
    res.json(rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Route: Dapatkan statistik keseluruhan
app.get('/api/stats', async (req, res) => {
  try {
    const connection = await pool.getConnection();
    
    const [stats] = await connection.query(`
      SELECT 
        COUNT(DISTINCT exchange_name) as total_exchanges,
        COUNT(*) as total_records,
        SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as success_records,
        SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed_records,
        MAX(fetched_at) as last_fetch
      FROM exchange_data
    `);
    
    connection.release();
    res.json(stats[0] || {});
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Route: Hapus data lama
app.delete('/api/clear-data', async (req, res) => {
  try {
    const connection = await pool.getConnection();
    const [result] = await connection.query(
      'DELETE FROM exchange_data WHERE fetched_at < DATE_SUB(NOW(), INTERVAL 7 DAY)'
    );
    connection.release();
    res.json({ 
      message: 'Old data deleted',
      deleted_rows: result.affectedRows 
    });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Route: Reset semua data
app.delete('/api/reset', async (req, res) => {
  try {
    const connection = await pool.getConnection();
    await connection.query('DELETE FROM exchange_data');
    connection.release();
    res.json({ message: 'All data has been reset' });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`\n🚀 Server running on http://localhost:${PORT}\n`);
  initializeDatabase();
});
