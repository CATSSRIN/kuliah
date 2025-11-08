-- Updated schema for the `tickers` table
CREATE TABLE tickers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    symbol VARCHAR(255) NOT NULL UNIQUE, -- Ensure unique symbols
    priceChangePercentage FLOAT,
    lastPrice FLOAT,
    volume FLOAT,
    timestamp DATETIME
);