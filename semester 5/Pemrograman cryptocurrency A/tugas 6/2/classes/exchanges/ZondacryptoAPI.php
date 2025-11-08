<?php

require_once __DIR__ . '/../ExchangeAPI.php';

class ZondacryptoAPI extends ExchangeAPI {
    
    public function fetchTickers() {
        try {
            $url = $this->baseUrl . $this->endpoints['ticker'];
            $response = $this->makeRequest($url);
            
            $tickers = [];
            if (isset($response['items']) && is_array($response['items'])) {
                foreach ($response['items'] as $ticker) {
                    $symbol = $ticker['market']['code'] ?? '';
                    list($base, $quote) = $this->parseSymbol($symbol);
                    
                    $tickerData = [
                        'symbol' => $symbol,
                        'base_currency' => $base,
                        'quote_currency' => $quote,
                        'last_price' => $ticker['rate'] ?? 0,
                        'high_24h' => $ticker['highestBid'] ?? 0,
                        'low_24h' => $ticker['lowestAsk'] ?? 0,
                        'volume_24h' => $ticker['volume'] ?? 0,
                        'timestamp' => strtotime($ticker['time'] ?? 'now')
                    ];
                    
                    $this->saveTicker($tickerData);
                    $tickers[] = $tickerData;
                }
            }
            
            $this->updateExchangeStatus('active');
            return $tickers;
            
        } catch (Exception $e) {
            $this->updateExchangeStatus('maintenance');
            error_log("Zondacrypto API Error: " . $e->getMessage());
            return [];
        }
    }
    
    public function fetchOrderbook($symbol) {
        try {
            $url = $this->baseUrl . $this->endpoints['orderbook'] . '/' . $symbol;
            return $this->makeRequest($url);
        } catch (Exception $e) {
            error_log("Zondacrypto Orderbook Error: " . $e->getMessage());
            return [];
        }
    }
    
    public function fetchTradingPairs() {
        return $this->fetchTickers();
    }
    
    private function parseSymbol($symbol) {
        $parts = explode('-', $symbol);
        return [
            $parts[0] ?? 'UNKNOWN',
            $parts[1] ?? 'PLN'
        ];
    }
}