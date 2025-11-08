# Crypto Exchange Aggregator

Platform untuk mengagregasi data dari berbagai cryptocurrency exchange menggunakan API mereka.

## Features

- ✅ Integrasi dengan 32+ cryptocurrency exchanges
- ✅ Automatic data fetching via cron jobs
- ✅ MySQL database storage
- ✅ Web dashboard untuk visualisasi data
- ✅ Kategorisasi exchange (Centralized, DEX, P2P, dll)
- ✅ Real-time ticker data
- ✅ Market statistics
- ✅ API call logging
- ✅ Automatic data cleanup

## Tech Stack

- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Frontend:** Bootstrap 5, HTML5, CSS3, JavaScript
- **Cron:** Linux Crontab

## Installation

### 1. Clone Repository
```bash
git clone <repository-url>
cd crypto-aggregator
```

### 2. Database Setup
```bash
mysql -u root -p < database/schema.sql
```

### 3. Configuration
Edit `config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'crypto_aggregator');
```

### 4. Set Permissions
```bash
chmod -R 755 public/
chmod -R 777 logs/
mkdir -p logs
```

### 5. Web Server Configuration

#### Apache (.htaccess)
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

#### Nginx
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 6. Setup Cron Jobs
```bash
crontab -e
```

Add:
```bash
*/5 * * * * /usr/bin/php /path/to/cronjobs/fetch_all_exchanges.php
```

## Project Structure

```
crypto-aggregator/
├── config/
│   └── config.php              # Configuration file
├── database/
│   └── schema.sql              # Database schema
├── classes/
│   ├── Database.php            # Database connection class
│   ├── ExchangeAPI.php         # Base API class
│   └── exchanges/              # Exchange-specific implementations
│       ├── BiboxAPI.php
│       ├── CoinCornerAPI.php
│       └── ZondacryptoAPI.php
├── cronjobs/
│   ├── fetch_all_exchanges.php # Main cron job
│   └── CRONTAB_SETUP.md        # Cron setup guide
├── public/
│   ├── index.php               # Dashboard
│   ├── category.php            # Category page
│   ├── exchange_detail.php     # Exchange details
│   └── assets/
│       └── css/
│           └── style.css       # Custom styles
├── logs/                       # Log files
└── README.md
```

## API Documentation

### Supported Exchanges

1. **Centralized Exchanges:**
   - Bibox
   - CoinCorner
   - Zondacrypto
   - Kanga Exchange
   - Vindax
   - Bitcoiva
   - (dan lainnya...)

2. **DEX (Decentralized):**
   - GroveX

3. **P2P:**
   - Localtrade