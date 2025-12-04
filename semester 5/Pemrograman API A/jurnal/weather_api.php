<?php
/**
 * RESTful API Cuaca BMKG
 * Aplikasi untuk mengintegrasikan data cuaca dari BMKG
 * Author: Peneliti
 * Version: 1.0.0
 * Date: 2025
 */

// Konfigurasi Header dan CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

// Kelas untuk Menangani API Cuaca BMKG
class WeatherAPI {
    
    private $db;
    private $db_host = 'localhost';
    private $db_user = 'root';
    private $db_pass = '';
    private $db_name = 'weather_bmkg_db';
    private $api_key = 'your_bmkg_api_key_here';
    private $bmkg_endpoint = 'https://api.bmkg.go.id/';
    
    public function __construct() {
        $this->connectDatabase();
        $this->initializeDatabase();
    }
    
    /**
     * Koneksi ke Database
     */
    private function connectDatabase() {
        try {
            $this->db = new PDO(
                "mysql:host={$this->db_host};dbname={$this->db_name}",
                $this->db_user,
                $this->db_pass,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        } catch (PDOException $e) {
            $this->sendError('Database connection error: ' . $e->getMessage(), 500);
            exit;
        }
    }
    
    /**
     * Inisialisasi Database dan Tabel
     */
    private function initializeDatabase() {
        try {
            // Cek apakah database sudah ada
            $stmt = $this->db->query("SELECT 1 FROM weather_data LIMIT 1");
        } catch (PDOException $e) {
            // Buat tabel jika belum ada
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS weather_data (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    province_name VARCHAR(100) NOT NULL,
                    city_name VARCHAR(100) NOT NULL,
                    temperature DECIMAL(5,2),
                    humidity INT,
                    wind_speed DECIMAL(5,2),
                    wind_direction VARCHAR(50),
                    rainfall DECIMAL(8,2),
                    weather_condition VARCHAR(100),
                    visibility INT,
                    pressure DECIMAL(8,2),
                    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                );
            ");
            
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS weather_forecast (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    province_name VARCHAR(100) NOT NULL,
                    city_name VARCHAR(100) NOT NULL,
                    forecast_date DATE NOT NULL,
                    forecast_time TIME,
                    temperature_min DECIMAL(5,2),
                    temperature_max DECIMAL(5,2),
                    weather_condition VARCHAR(100),
                    rainfall_probability INT,
                    wind_speed DECIMAL(5,2),
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    UNIQUE KEY unique_forecast (city_name, forecast_date, forecast_time)
                );
            ");
            
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS api_logs (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    endpoint VARCHAR(255) NOT NULL,
                    method VARCHAR(10) NOT NULL,
                    status_code INT,
                    response_time FLOAT,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                );
            ");
        }
    }
    
    /**
     * GET - Ambil Data Cuaca Real-time
     */
    public function getWeatherRealtime($city) {
        try {
            $start_time = microtime(true);
            
            // Validasi input
            if (empty($city)) {
                $this->sendError('City parameter is required', 400);
                return;
            }
            
            // Sanitasi input
            $city = $this->sanitizeInput($city);
            
            // Query data dari database
            $stmt = $this->db->prepare("
                SELECT * FROM weather_data 
                WHERE city_name = :city 
                ORDER BY timestamp DESC 
                LIMIT 1
            ");
            
            $stmt->bindParam(':city', $city, PDO::PARAM_STR);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                // Jika tidak ada di database, ambil dari BMKG API
                $result = $this->fetchFromBMKG($city);
                
                if ($result) {
                    $this->saveWeatherData($result);
                } else {
                    $this->sendError('Weather data not found for city: ' . $city, 404);
                    return;
                }
            }
            
            $response_time = microtime(true) - $start_time;
            $this->logAPICall('/weather/realtime', 'GET', 200, $response_time);
            
            $this->sendSuccess($result, 'Weather data retrieved successfully');
            
        } catch (Exception $e) {
            $this->sendError('Error retrieving weather data: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * GET - Ambil Data Cuaca Ramalan
     */
    public function getWeatherForecast($city, $days = 5) {
        try {
            $start_time = microtime(true);
            
            if (empty($city)) {
                $this->sendError('City parameter is required', 400);
                return;
            }
            
            if ($days < 1 || $days > 10) {
                $this->sendError('Days must be between 1 and 10', 400);
                return;
            }
            
            $city = $this->sanitizeInput($city);
            
            $stmt = $this->db->prepare("
                SELECT * FROM weather_forecast 
                WHERE city_name = :city 
                AND forecast_date >= CURDATE() 
                AND forecast_date <= DATE_ADD(CURDATE(), INTERVAL :days DAY)
                ORDER BY forecast_date ASC, forecast_time ASC
            ");
            
            $stmt->bindParam(':city', $city, PDO::PARAM_STR);
            $stmt->bindParam(':days', $days, PDO::PARAM_INT);
            $stmt->execute();
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($results)) {
                $this->sendError('Forecast data not found for city: ' . $city, 404);
                return;
            }
            
            $response_time = microtime(true) - $start_time;
            $this->logAPICall('/weather/forecast', 'GET', 200, $response_time);
            
            $this->sendSuccess([
                'city' => $city,
                'days' => $days,
                'forecasts' => $results,
                'count' => count($results)
            ], 'Forecast data retrieved successfully');
            
        } catch (Exception $e) {
            $this->sendError('Error retrieving forecast data: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * GET - Ambil Data Cuaca untuk Multiple Cities
     */
    public function getWeatherMultipleCities($cities) {
        try {
            $start_time = microtime(true);
            
            if (empty($cities)) {
                $this->sendError('Cities parameter is required', 400);
                return;
            }
            
            // Parse cities (format: city1,city2,city3)
            $city_array = explode(',', $cities);
            $city_array = array_map('trim', $city_array);
            
            $results = [];
            foreach ($city_array as $city) {
                $city = $this->sanitizeInput($city);
                
                $stmt = $this->db->prepare("
                    SELECT * FROM weather_data 
                    WHERE city_name = :city 
                    ORDER BY timestamp DESC 
                    LIMIT 1
                ");
                
                $stmt->bindParam(':city', $city, PDO::PARAM_STR);
                $stmt->execute();
                
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($result) {
                    $results[] = $result;
                }
            }
            
            if (empty($results)) {
                $this->sendError('No weather data found for provided cities', 404);
                return;
            }
            
            $response_time = microtime(true) - $start_time;
            $this->logAPICall('/weather/multiple', 'GET', 200, $response_time);
            
            $this->sendSuccess([
                'requested_cities' => count($city_array),
                'found_cities' => count($results),
                'data' => $results
            ], 'Multiple cities weather data retrieved');
            
        } catch (Exception $e) {
            $this->sendError('Error retrieving multiple cities data: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * POST - Simpan Data Cuaca Manual
     */
    public function saveWeather($data) {
        try {
            // Validasi data
            $required_fields = ['province_name', 'city_name', 'temperature', 'humidity'];
            foreach ($required_fields as $field) {
                if (!isset($data[$field])) {
                    $this->sendError('Missing required field: ' . $field, 400);
                    return;
                }
            }
            
            // Sanitasi input
            $province = $this->sanitizeInput($data['province_name']);
            $city = $this->sanitizeInput($data['city_name']);
            $temperature = floatval($data['temperature']);
            $humidity = intval($data['humidity']);
            $wind_speed = floatval($data['wind_speed'] ?? 0);
            $wind_direction = $this->sanitizeInput($data['wind_direction'] ?? '');
            $rainfall = floatval($data['rainfall'] ?? 0);
            $weather_condition = $this->sanitizeInput($data['weather_condition'] ?? 'Unknown');
            $visibility = intval($data['visibility'] ?? 0);
            $pressure = floatval($data['pressure'] ?? 0);
            
            // Validasi range
            if ($temperature < -50 || $temperature > 60) {
                $this->sendError('Temperature value out of valid range', 400);
                return;
            }
            
            if ($humidity < 0 || $humidity > 100) {
                $this->sendError('Humidity must be between 0 and 100', 400);
                return;
            }
            
            // Insert ke database
            $stmt = $this->db->prepare("
                INSERT INTO weather_data 
                (province_name, city_name, temperature, humidity, wind_speed, wind_direction, 
                 rainfall, weather_condition, visibility, pressure, timestamp) 
                VALUES 
                (:province, :city, :temp, :humidity, :wind_speed, :wind_direction, 
                 :rainfall, :weather_condition, :visibility, :pressure, NOW())
            ");
            
            $stmt->bindParam(':province', $province);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':temp', $temperature);
            $stmt->bindParam(':humidity', $humidity);
            $stmt->bindParam(':wind_speed', $wind_speed);
            $stmt->bindParam(':wind_direction', $wind_direction);
            $stmt->bindParam(':rainfall', $rainfall);
            $stmt->bindParam(':weather_condition', $weather_condition);
            $stmt->bindParam(':visibility', $visibility);
            $stmt->bindParam(':pressure', $pressure);
            
            $stmt->execute();
            
            $this->logAPICall('/weather/save', 'POST', 201, 0);
            
            $this->sendSuccess([
                'id' => $this->db->lastInsertId(),
                'city' => $city,
                'temperature' => $temperature,
                'humidity' => $humidity
            ], 'Weather data saved successfully', 201);
            
        } catch (PDOException $e) {
            $this->sendError('Error saving weather data: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Fetch Data dari BMKG API
     */
    private function fetchFromBMKG($city) {
        try {
            $url = $this->bmkg_endpoint . 'weather?city=' . urlencode($city) . '&key=' . $this->api_key;
            
            $context = stream_context_create([
                'http' => [
                    'timeout' => 10,
                    'method' => 'GET'
                ]
            ]);
            
            $response = @file_get_contents($url, false, $context);
            
            if ($response === false) {
                return null;
            }
            
            $data = json_decode($response, true);
            
            if (!$data || !isset($data['data'])) {
                return null;
            }
            
            return $data['data'];
            
        } catch (Exception $e) {
            error_log('Error fetching from BMKG: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Simpan Data Cuaca ke Database
     */
    private function saveWeatherData($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT IGNORE INTO weather_data 
                (province_name, city_name, temperature, humidity, wind_speed, weather_condition, timestamp) 
                VALUES 
                (:province, :city, :temp, :humidity, :wind_speed, :condition, NOW())
            ");
            
            $stmt->bindParam(':province', $data['province'] ?? 'Unknown');
            $stmt->bindParam(':city', $data['city']);
            $stmt->bindParam(':temp', $data['temp'] ?? 0);
            $stmt->bindParam(':humidity', $data['humidity'] ?? 0);
            $stmt->bindParam(':wind_speed', $data['wind_speed'] ?? 0);
            $stmt->bindParam(':condition', $data['description'] ?? 'Unknown');
            
            $stmt->execute();
            
        } catch (Exception $e) {
            error_log('Error saving weather data: ' . $e->getMessage());
        }
    }
    
    /**
     * Sanitasi Input
     */
    private function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Log API Call
     */
    private function logAPICall($endpoint, $method, $status_code, $response_time) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO api_logs (endpoint, method, status_code, response_time) 
                VALUES (:endpoint, :method, :status, :time)
            ");
            
            $stmt->bindParam(':endpoint', $endpoint);
            $stmt->bindParam(':method', $method);
            $stmt->bindParam(':status', $status_code);
            $stmt->bindParam(':time', $response_time);
            
            $stmt->execute();
            
        } catch (Exception $e) {
            error_log('Error logging API call: ' . $e->getMessage());
        }
    }
    
    /**
     * Send Success Response
     */
    private function sendSuccess($data, $message = '', $code = 200) {
        http_response_code($code);
        echo json_encode([
            'success' => true,
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Send Error Response
     */
    private function sendError($message, $code = 400) {
        http_response_code($code);
        echo json_encode([
            'success' => false,
            'code' => $code,
            'message' => $message,
            'data' => null,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
}

// Router untuk API Endpoints
class Router {
    
    private $weather_api;
    private $method;
    private $endpoint;
    private $params;
    
    public function __construct() {
        $this->weather_api = new WeatherAPI();
        $this->parseRequest();
        $this->route();
    }
    
    private function parseRequest() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        
        // Parse URL
        $request_uri = $_SERVER['REQUEST_URI'];
        $parsed_url = parse_url($request_uri);
        $path = $parsed_url['path'];
        
        // Hapus prefix /api/v1
        $path = preg_replace('/^\/api\/v1/', '', $path);
        $path = trim($path, '/');
        
        // Split path
        $parts = explode('/', $path);
        $this->endpoint = !empty($parts[0]) ? $parts[0] : '';
        
        // Parse query params
        $this->params = $_GET;
        
        // Parse JSON body untuk POST/PUT
        if (in_array($this->method, ['POST', 'PUT'])) {
            $input = file_get_contents('php://input');
            $json_data = json_decode($input, true);
            $this->params = array_merge($this->params, $json_data ?? []);
        }
    }
    
    private function route() {
        switch ($this->endpoint) {
            case 'weather':
                if ($this->method === 'GET') {
                    if (isset($this->params['type']) && $this->params['type'] === 'forecast') {
                        $city = $this->params['city'] ?? '';
                        $days = $this->params['days'] ?? 5;
                        $this->weather_api->getWeatherForecast($city, $days);
                    } elseif (isset($this->params['cities'])) {
                        $this->weather_api->getWeatherMultipleCities($this->params['cities']);
                    } else {
                        $city = $this->params['city'] ?? '';
                        $this->weather_api->getWeatherRealtime($city);
                    }
                } elseif ($this->method === 'POST') {
                    $this->weather_api->saveWeather($this->params);
                }
                break;
            
            case 'health':
                http_response_code(200);
                echo json_encode([
                    'status' => 'OK',
                    'service' => 'Weather API BMKG',
                    'version' => '1.0.0',
                    'timestamp' => date('Y-m-d H:i:s')
                ]);
                break;
            
            default:
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'code' => 404,
                    'message' => 'Endpoint not found'
                ]);
        }
    }
}

// Handle OPTIONS request untuk CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Initialize Router
new Router();
?>
