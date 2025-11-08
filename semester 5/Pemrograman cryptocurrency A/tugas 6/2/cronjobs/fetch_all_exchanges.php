<?php
/**
 * Cron Job untuk fetch data dari semua exchange
 * Jalankan setiap 1-5 menit: */5 * * * * /usr/bin/php /path/to/fetch_all_exchanges.php
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/ExchangeAPI.php';

// Load all exchange APIs
foreach (glob(__DIR__ . '/../classes/exchanges/*API.php') as $file) {
    require_once $file;
}

class CronJob {
    private $db;
    private $startTime;
    private $logFile;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->startTime = microtime(true);
        $this->logFile = __DIR__ . '/../logs/cron_' . date('Y-m-d') . '.log';
        
        $this->log("=== Cron Job Started ===");
    }
    
    public function run() {
        global $apiConfig;
        
        // Get all active exchanges
        $exchanges = $this->db->fetchAll(
            "SELECT * FROM exchanges WHERE status != 'inactive' ORDER BY name"
        );
        
        $this->log("Found " . count($exchanges) . " exchanges to process");
        
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($exchanges as $exchange) {
            try {
                $this->log("Processing: {$exchange['name']} ({$exchange['code']})");
                
                $config = $apiConfig[$exchange['code']] ?? $apiConfig['generic'];
                
                // Skip if no API URL configured
                if (empty($config['base_url'])) {
                    $this->log("  - Skipped: No API URL configured");
                    continue;
                }
                
                $className = $this->getAPIClassName($exchange['code']);
                
                if (!class_exists($className)) {
                    $this->log("  - Warning: Class {$className} not found, skipping");
                    continue;
                }
                
                $api = new $className($exchange['code'], $config);
                
                // Fetch tickers
                $tickers = $api->fetchTickers();
                $tickerCount = count($tickers);
                
                if ($tickerCount > 0) {
                    $this->log("  - Success: Fetched {$tickerCount} tickers");
                    $this->updateMarketStats($exchange['id'], $tickers);
                    $successCount++;
                } else {
                    $this->log("  - Warning: No tickers fetched");
                    $errorCount++;
                }
                
                // Small delay between exchanges
                sleep(1);
                
            } catch (Exception $e) {
                $this->log("  - Error: " . $e->getMessage());
                $errorCount++;
            }
        }
        
        $duration = round(microtime(true) - $this->startTime, 2);
        $this->log("=== Cron Job Completed ===");
        $this->log("Duration: {$duration}s | Success: {$successCount} | Errors: {$errorCount}");
        
        // Cleanup old data
        $this->cleanupOldData();
    }
    
    private function getAPIClassName($code) {
        // Convert code to class name (e.g., 'bibox' -> 'BiboxAPI')
        $parts = explode('_', $code);
        $className = '';
        foreach ($parts as $part) {
            $className .= ucfirst($part);
        }
        return $className . 'API';
    }
    
    private function updateMarketStats($exchangeId, $tickers) {
        if (empty($tickers)) return;
        
        $totalVolume = 0;
        $avgPriceChange = 0;
        $count = 0;
        
        foreach ($tickers as $ticker) {
            $totalVolume += $ticker['volume_24h'] ?? 0;
            $avgPriceChange += $ticker['price_change_percent_24h'] ?? 0;
            $count++;
        }
        
        if ($count > 0) {
            $avgPriceChange = $avgPriceChange / $count;
        }
        
        $this->db->insert('market_stats', [
            'exchange_id' => $exchangeId,
            'total_pairs' => $count,
            'total_volume_24h' => $totalVolume,
            'avg_price_change_24h' => $avgPriceChange,
            'timestamp' => time()
        ]);
    }
    
    private function cleanupOldData() {
        $this->log("Cleaning up old data...");
        
        // Keep only last 7 days of ticker data
        $this->db->query("DELETE FROM tickers WHERE created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)");
        
        // Keep only last 24 hours of orderbook data
        $this->db->query("DELETE FROM orderbooks WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 DAY)");
        
        // Keep only last 30 days of API logs
        $this->db->query("DELETE FROM api_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)");
        
        // Keep only last 30 days of market stats
        $this->db->query("DELETE FROM market_stats WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)");
        
        $this->log("Cleanup completed");
    }
    
    private function log($message) {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] {$message}\n";
        
        echo $logMessage;
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }
}

// Run the cron job
try {
    $cron = new CronJob();
    $cron->run();
} catch (Exception $e) {
    error_log("Cron Job Fatal Error: " . $e->getMessage());
    exit(1);
}