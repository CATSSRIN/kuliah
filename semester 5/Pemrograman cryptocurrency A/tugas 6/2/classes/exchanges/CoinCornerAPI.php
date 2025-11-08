<?php

require_once __DIR__ . '/../ExchangeAPI.php';

class CoinCornerAPI extends ExchangeAPI {
    
    public function fetchTickers() {
        try {
            $url = $this->baseUrl . $this->endpoints['ticker'];
            $response = $this->makeRequest($url);
            
            $tickers = [];
            foreach ($response as $market => $ticker) {
                list($base, $quote) = $this->parseSymbol($market);
                
                $tickerData = [
                    'symbol' => $market,
                    'base_currency' => $base,
                    'quote_currency' => $quote,
                    'last_price' => $ticker['last'] ?? 0,
                    'bid_price' => $ticker['bid'] ?? 0,
                    'ask_price' => $ticker['ask'] ?? 0,
                    'high_24h' => $ticker['high'] ?? 0,
                    'low_24h' => $ticker['low'] ?? 0,
                    'volume_24h' => $ticker['volume'] ?? 0,
                    'timestamp' => time()
                ];
                
                $this->saveTicker($tickerData);
                $tickers[] = $tickerData;
            }
            
            $this->updateExchangeStatus('active');
            return $tickers;
            
        } catch (Exception $e) {
            $this->updateExchangeStatus('maintenance');
            error_log("CoinCorner API Error: " . $e->getMessage());
            return [];
        }
    }
    
    public function fetchOrderbook($symbol) {
        try {
            $url = $this->baseUrl . $this->endpoints['orderbook'] . '/' . $symbol;
            return $this->makeRequest($url);
        } catch (Exception $e) {
            error_log("CoinCorner Orderbook Error: " . $e->getMessage());
            return [];
        }
    }
    
    public function fetchTradingPairs() {
        return $this->fetchTickers();
    }
    
    private function parseSymbol($symbol) {
        if (strpos($symbol, '/') !== false) {
            return explode('/', $symbol);
        }
        return [$symbol, 'USD'];
    }
}