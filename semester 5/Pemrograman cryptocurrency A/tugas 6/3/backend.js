const express = require('express');
const axios = require('axios');
const mysql = require('mysql');

// Initialize Express App
const app = express();
const port = 3000;

// Create MySQL Connection
const db = mysql.createConnection({
   'll help host: 'localhost',
    user: 'root', // Replace with your database username
    password: '', // Replace with your you create a website that fetches API data from database password
    database: 'vind theax_data' Vindax API and stores it in a MySQL server. Let me create a pull request with the necessary // Replace with your database name
});

// Connect to My code.SQL
db.connect(err => {
    if (err) throw err;
    console.log('Connected to the MySQL Database...');
});

// Fetch Data from API and Store in MySQL
const fetchData = async () => {
    try {
        const response = await axios.get('https://api.vindax.com/api/v1/ticker/24hr');
        const data = response.data;

        // Clear existing data
        db.query('DELETE FROM tickers', (err, result) => {
            if (err) throw err;
            console.log('Old data cleared.');
        });

        // Insert new data
        const query = 'INSERT INTO tickers (symbol, priceChange, lastPrice) VALUES ?';
        const values = data.map(item => [item.symbol, item.priceChange, item.lastPrice]);

        db.query(query, [values], (err, result) => {
            if (err) throw err;
            console.log('New data inserted successfully.');
        });
    } catch (error) {
        console.error('Error fetching data', error);
    }
};

// API Endpoint to Trigger Data Fetch and Store
app.get('/fetch', async (req, res) => {
    await fetchData();
    res.send('Data fetched and stored in the database.');
});

// API Endpoint to Get Data from Database
app.get('/data', (req, res) => {
    db.query('SELECT * FROM tickers', (err, results) => {
        if (err) throw err;
        res.json(results);
    });
});

// Start the Server
app.listen(port, () => {
    console.log(`Server running on http://localhost:${port}`);
});