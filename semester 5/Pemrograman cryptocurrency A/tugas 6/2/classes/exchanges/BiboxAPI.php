<?php

require_once __DIR__ . '/../ExchangeAPI.php';

class BiboxAPI extends ExchangeAPI {
    
    public function fetchTickers() {
        try {
            $url = $this->baseUrl . $this->endpoints['ticker'] . '?cmd=ticker&pair=ALL';
            $response = $this->makeRequest($url);
            
            if (!isset($response['result']) || !is_array($response['result'])) {
                throw new Exception("Invalid response format");
            }
            
            $tickers = [];
            foreach ($response['result'] as $ticker) {
                $symbol = $ticker['pair'] ?? '';
                list($base, $quote) = $this->parseSymbol($symbol);
                
                $tickers[] = [
                    'symbol' => $symbol,
                    'base_currency' => $base,
                    'quote_currency' => $quote,
                    'last_price' => $ticker['last'] ?? 0,
                    'bid_price' => $ticker['buy'] ?? 0,
                    'ask_price' => $ticker['sell'] ?? 0,
                    'high_24h' => $ticker['high'] ?? 0,
                    'low_24h' => $ticker['low'] ?? 0,
                    'volume_24h' => $ticker['vol'] ?? 0,
                    'price_change_percent_24h' => $ticker['percent'] ?? 0,
                    'timestamp' => $ticker['timestamp'] ?? time()
                ];
                
                $this->saveTicker($tickers[count($tickers) - 1]);
            }
            
            $this->updateExchangeStatus('active');
            return $tickers;
            
        } catch (Exception $e) {
            $this->updateExchangeStatus('maintenance');
            error_log("Bibox API Error: " . $e->getMessage());
            return [];
        }
    }
    
    public function fetchOrderbook($symbol) {
        try {
            $url = $this->baseUrl . $this->endpoints['orderbook'] . '?cmd=depth&pair=' . $symbol;
            $response = $this->makeRequest($url);
            
            return $response['result'] ?? [];
        } catch (Exception $e) {
            error_log("Bibox Orderbook Error: " . $e->getMessage());
            return [];
        }
    }
    
    public function fetchTradingPairs() {
        return $this->fetchTickers();
    }
    
    private function parseSymbol($symbol) {
        $parts = explode('_', $symbol);
        return [
            $parts[0] ?? 'UNKNOWN',
            $parts[1] ?? 'USDT'
        ];
    }
}