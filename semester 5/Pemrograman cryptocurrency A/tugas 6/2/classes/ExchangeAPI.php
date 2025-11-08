<?php

abstract class ExchangeAPI {
    protected $db;
    protected $exchangeId;
    protected $exchangeCode;
    protected $baseUrl;
    protected $apiKey;
    protected $endpoints;
    protected $rateLimitDelay;
    
    public function __construct($exchangeCode, $config) {
        $this->db = Database::getInstance();
        $this->exchangeCode = $exchangeCode;
        $this->baseUrl = $config['base_url'] ?? '';
        $this->apiKey = $config['api_key'] ?? '';
        $this->endpoints = $config['endpoints'] ?? [];
        $this->rateLimitDelay = RATE_LIMIT_DELAY;
        
        // Get exchange ID from database
        $exchange = $this->db->fetchOne(
            "SELECT id FROM exchanges WHERE code = :code",
            ['code' => $exchangeCode]
        );
        $this->exchangeId = $exchange['id'] ?? null;
    }
    
    protected function makeRequest($url, $method = 'GET', $data = [], $headers = []) {
        $ch = curl_init();
        
        $defaultHeaders = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];
        
        if ($this->apiKey) {
            $defaultHeaders[] = 'X-API-Key: ' . $this->apiKey;
        }
        
        $headers = array_merge($defaultHeaders, $headers);
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => TIMEOUT,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_FOLLOWLOCATION => true
        ]);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $startTime = microtime(true);
        $response = curl_exec($ch);
        $responseTime = round((microtime(true) - $startTime) * 1000);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        // Log API call
        $this->logApiCall($url, $httpCode, $responseTime, $error);
        
        if ($error) {
            throw new Exception("cURL Error: {$error}");
        }
        
        if ($httpCode !== 200) {
            throw new Exception("HTTP Error {$httpCode}: {$response}");
        }
        
        // Rate limiting
        usleep($this->rateLimitDelay * 1000000);
        
        return json_decode($response, true);
    }
    
    protected function logApiCall($endpoint, $statusCode, $responseTime, $error = null) {
        if ($this->exchangeId) {
            $this->db->insert('api_logs', [
                'exchange_id' => $this->exchangeId,
                'endpoint' => $endpoint,
                'status_code' => $statusCode,
                'response_time' => $responseTime,
                'error_message' => $error
            ]);
        }
    }
    
    public function updateExchangeStatus($status) {
        if ($this->exchangeId) {
            $this->db->update(
                'exchanges',
                [
                    'status' => $status,
                    'last_update' => date('Y-m-d H:i:s')
                ],
                'id = :id',
                ['id' => $this->exchangeId]
            );
        }
    }
    
    abstract public function fetchTickers();
    abstract public function fetchOrderbook($symbol);
    abstract public function fetchTradingPairs();
    
    public function saveTicker($tickerData) {
        // Get or create trading pair
        $pairId = $this->getOrCreateTradingPair(
            $tickerData['symbol'],
            $tickerData['base_currency'],
            $tickerData['quote_currency']
        );
        
        return $this->db->insert('tickers', [
            'exchange_id' => $this->exchangeId,
            'trading_pair_id' => $pairId,
            'symbol' => $tickerData['symbol'],
            'last_price' => $tickerData['last_price'] ?? null,
            'bid_price' => $tickerData['bid_price'] ?? null,
            'ask_price' => $tickerData['ask_price'] ?? null,
            'high_24h' => $tickerData['high_24h'] ?? null,
            'low_24h' => $tickerData['low_24h'] ?? null,
            'volume_24h' => $tickerData['volume_24h'] ?? null,
            'volume_quote_24h' => $tickerData['volume_quote_24h'] ?? null,
            'price_change_24h' => $tickerData['price_change_24h'] ?? null,
            'price_change_percent_24h' => $tickerData['price_change_percent_24h'] ?? null,
            'timestamp' => $tickerData['timestamp'] ?? time()
        ]);
    }
    
    protected function getOrCreateTradingPair($symbol, $baseCurrency, $quoteCurrency) {
        $pair = $this->db->fetchOne(
            "SELECT id FROM trading_pairs WHERE exchange_id = :exchange_id AND symbol = :symbol",
            ['exchange_id' => $this->exchangeId, 'symbol' => $symbol]
        );
        
        if ($pair) {
            return $pair['id'];
        }
        
        return $this->db->insert('trading_pairs', [
            'exchange_id' => $this->exchangeId,
            'symbol' => $symbol,
            'base_currency' => $baseCurrency,
            'quote_currency' => $quoteCurrency
        ]);
    }
}