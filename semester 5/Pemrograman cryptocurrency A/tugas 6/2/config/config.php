<?php

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'crypto_aggregator');

// API Configuration
$apiConfig = [
    'cryptonex' => [
        'base_url' => 'https://api.cryptonex.org',
        'api_key' => '',
        'endpoints' => [
            'ticker' => '/api/v1/ticker',
            'orderbook' => '/api/v1/orderbook',
            'trades' => '/api/v1/trades'
        ]
    ],
    'bibox' => [
        'base_url' => 'https://api.bibox.com',
        'api_key' => '',
        'endpoints' => [
            'ticker' => '/v1/mdata',
            'orderbook' => '/v1/mdata/orderBook',
            'trades' => '/v1/mdata/deals'
        ]
    ],
    'coincorner' => [
        'base_url' => 'https://api.coincorner.com',
        'api_key' => '',
        'endpoints' => [
            'ticker' => '/api/ticker',
            'orderbook' => '/api/orderbook'
        ]
    ],
    'zondacrypto' => [
        'base_url' => 'https://api.zondacrypto.com',
        'api_key' => '',
        'endpoints' => [
            'ticker' => '/rest/trading/ticker',
            'orderbook' => '/rest/trading/orderbook'
        ]
    ],
    'kanga' => [
        'base_url' => 'https://api.kanga.exchange',
        'api_key' => '',
        'endpoints' => [
            'ticker' => '/api/market/tickers',
            'orderbook' => '/api/market/orderbook'
        ]
    ],
    // Generic endpoints untuk exchange lainnya
    'generic' => [
        'base_url' => '',
        'api_key' => '',
        'endpoints' => [
            'ticker' => '/api/ticker',
            'orderbook' => '/api/orderbook',
            'trades' => '/api/trades'
        ]
    ]
];

// General Settings
define('TIMEOUT', 30);
define('MAX_RETRIES', 3);
define('RATE_LIMIT_DELAY', 1); // seconds between requests
define('LOG_LEVEL', 'INFO'); // DEBUG, INFO, WARNING, ERROR

// Cache Settings
define('CACHE_ENABLED', true);
define('CACHE_DURATION', 60); // seconds

return $apiConfig;