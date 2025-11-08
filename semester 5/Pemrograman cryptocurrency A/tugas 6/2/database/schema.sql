-- Database Schema untuk Crypto Exchange Aggregator

CREATE DATABASE IF NOT EXISTS crypto_aggregator;
USE crypto_aggregator;

-- Table untuk menyimpan informasi exchange
CREATE TABLE exchanges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
    api_url VARCHAR(255),
    status ENUM('active', 'inactive', 'maintenance') DEFAULT 'active',
    category VARCHAR(50),
    last_update TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table untuk menyimpan trading pairs
CREATE TABLE trading_pairs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exchange_id INT NOT NULL,
    symbol VARCHAR(50) NOT NULL,
    base_currency VARCHAR(20) NOT NULL,
    quote_currency VARCHAR(20) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (exchange_id) REFERENCES exchanges(id) ON DELETE CASCADE,
    INDEX idx_symbol (symbol),
    INDEX idx_exchange (exchange_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table untuk menyimpan ticker data
CREATE TABLE tickers (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    exchange_id INT NOT NULL,
    trading_pair_id INT NOT NULL,
    symbol VARCHAR(50) NOT NULL,
    last_price DECIMAL(20, 8),
    bid_price DECIMAL(20, 8),
    ask_price DECIMAL(20, 8),
    high_24h DECIMAL(20, 8),
    low_24h DECIMAL(20, 8),
    volume_24h DECIMAL(30, 8),
    volume_quote_24h DECIMAL(30, 8),
    price_change_24h DECIMAL(20, 8),
    price_change_percent_24h DECIMAL(10, 4),
    timestamp BIGINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (exchange_id) REFERENCES exchanges(id) ON DELETE CASCADE,
    FOREIGN KEY (trading_pair_id) REFERENCES trading_pairs(id) ON DELETE CASCADE,
    INDEX idx_symbol_time (symbol, created_at),
    INDEX idx_exchange_time (exchange_id, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table untuk menyimpan orderbook data
CREATE TABLE orderbooks (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    exchange_id INT NOT NULL,
    trading_pair_id INT NOT NULL,
    symbol VARCHAR(50) NOT NULL,
    side ENUM('buy', 'sell') NOT NULL,
    price DECIMAL(20, 8) NOT NULL,
    quantity DECIMAL(30, 8) NOT NULL,
    total DECIMAL(30, 8),
    timestamp BIGINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (exchange_id) REFERENCES exchanges(id) ON DELETE CASCADE,
    FOREIGN KEY (trading_pair_id) REFERENCES trading_pairs(id) ON DELETE CASCADE,
    INDEX idx_symbol_side (symbol, side, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table untuk menyimpan market statistics
CREATE TABLE market_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exchange_id INT NOT NULL,
    total_pairs INT DEFAULT 0,
    total_volume_24h DECIMAL(30, 8),
    total_trades_24h BIGINT,
    avg_price_change_24h DECIMAL(10, 4),
    timestamp BIGINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (exchange_id) REFERENCES exchanges(id) ON DELETE CASCADE,
    INDEX idx_exchange_time (exchange_id, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table untuk log API calls
CREATE TABLE api_logs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    exchange_id INT NOT NULL,
    endpoint VARCHAR(255),
    status_code INT,
    response_time INT,
    error_message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (exchange_id) REFERENCES exchanges(id) ON DELETE CASCADE,
    INDEX idx_exchange_time (exchange_id, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert initial exchange data
INSERT INTO exchanges (name, code, category) VALUES
('Cryptonex', 'cryptonex', 'centralized'),
('AIA Exchange', 'aia', 'centralized'),
('Localtrade', 'localtrade', 'p2p'),
('Salavi', 'salavi', 'centralized'),
('Vindax', 'vindax', 'centralized'),
('Cat.Ex', 'catex', 'centralized'),
('SpireX', 'spirex', 'centralized'),
('BitxEx', 'bitxex', 'centralized'),
('Bibox', 'bibox', 'centralized'),
('Crypton Exchange', 'crypton', 'centralized'),
('HKD.com', 'hkd', 'centralized'),
('CoinCorner', 'coincorner', 'centralized'),
('Bitcoiva', 'bitcoiva', 'centralized'),
('Coinbase International', 'coinbase_intl', 'centralized'),
('Gleec BTC', 'gleecbtc', 'centralized'),
('BankCEX', 'bankcex', 'centralized'),
('GroveX', 'grovex', 'dex'),
('BitbabyExchange', 'bitbaby', 'centralized'),
('Cryptomus', 'cryptomus', 'payment'),
('Darkex Exchange', 'darkex', 'centralized'),
('BitTap', 'bittap', 'centralized'),
('CoinLocallay', 'coinlocallay', 'p2p'),
('IBIT Global', 'ibit', 'centralized'),
('Dzengi.com', 'dzengi', 'centralized'),
('BlockFin', 'blockfin', 'centralized'),
('Zondacrypto', 'zondacrypto', 'centralized'),
('Kinesis Money', 'kinesis', 'payment'),
('TRIV', 'triv', 'centralized'),
('BitradeX', 'bitradex', 'centralized'),
('Arkham', 'arkham', 'intelligence'),
('BitKan', 'bitkan', 'aggregator'),
('Kanga Exchange', 'kanga', 'centralized');