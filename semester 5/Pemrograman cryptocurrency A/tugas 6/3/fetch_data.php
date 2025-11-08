<?php
/**
 * Fetch Data Script
 *
 * Fetches data from the Vindax API, stores it in the database, and logs errors and timings.
 *
 * @package VindaxDataApp
 */

// Include the database configuration
require 'database_config.php';

// Log file paths
$logFile = 'api_log.txt';
$cacheFile = 'api_cache.json';

/**
 * Logs a message to the specified log file.
 *
 * @param string $message The message to log.
 */
function logMessage($message)
{
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

// Measure response time
$startTime = microtime(true);

// Fetch data from the Vindax API
$url = "https://api.vindax.com/api/v1/ticker/24hr";
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);

// Handle cURL errors
if (curl_errno($curl)) {
    $error_message = "cURL error: " . curl_error($curl);
    logMessage("Failed API call: $error_message");
    die($error_message);
}

// Calculate response time
$responseTime = microtime(true) - $startTime;
logMessage("API call completed in " . number_format($responseTime, 4) . " seconds.");

curl_close($curl);

// Decode JSON and cache it
$data = json_decode($response, true);
file_put_contents($cacheFile, $response);

// Validate data structure
if (!$data) {
    $error_message = "Failed to decode JSON: " . json_last_error_msg();
    logMessage("Failed API call: $error_message");
    die($error_message);
}

// Database Insertion Logic (unchanged)

// CRON JOB SETUP
// To set up a cron job, add this line to your crontab file:
// * * * * * /usr/bin/php /path/to/fetch_data.php
?>