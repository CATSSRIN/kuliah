# Dokumentasi API RESTful Cuaca BMKG

## 1. INSTALASI DAN SETUP

### Persyaratan Sistem
- PHP 7.4+ dengan extension PDO dan MySQLi
- MySQL 5.7+ atau MariaDB 10.1+
- Apache/Nginx web server
- cURL untuk testing API

### Langkah Instalasi

#### 1.1 Setup Database
```bash
# Login ke MySQL
mysql -u root -p

# Buat database
CREATE DATABASE weather_bmkg_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE weather_bmkg_db;

# Tabel otomatis dibuat oleh aplikasi saat pertama kali dijalankan
```

#### 1.2 Setup File Project
```bash
# Copy weather_api.php ke document root
cp weather_api.php /var/www/html/api/v1/index.php

# Setup permission
chmod 755 /var/www/html/api/v1/index.php

# Create .htaccess untuk URL rewriting
cat > /var/www/html/api/v1/.htaccess << 'EOF'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /api/v1/
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
</IfModule>
EOF
```

#### 1.3 Konfigurasi Database Connection
Edit file `weather_api.php` dan sesuaikan:
```php
private $db_host = 'localhost';
private $db_user = 'root';
private $db_pass = 'your_password';
private $db_name = 'weather_bmkg_db';
private $api_key = 'your_bmkg_api_key';
```

#### 1.4 Setup Cron Job
```bash
# Copy script
cp cron_weather_api.sh /usr/local/bin/weather_api_cron.sh
chmod +x /usr/local/bin/weather_api_cron.sh

# Setup log directory
sudo mkdir -p /var/log/weather_api
sudo chmod 777 /var/log/weather_api

# Edit crontab
crontab -e

# Tambahkan line berikut:
*/30 * * * * /usr/local/bin/weather_api_cron.sh
```

#### 1.5 Verifikasi Setup
```bash
# Test API endpoint
curl -X GET "http://localhost/api/v1/weather?city=Jakarta"

# Expected response:
# {"success":true,"code":200,"message":"Weather data retrieved successfully",...}
```

---

## 2. API ENDPOINTS DOCUMENTATION

### 2.1 GET Weather Real-time Data

**Endpoint**: `GET /api/v1/weather`

**Parameters**:
- `city` (string, required): Nama kota yang dicari

**Example Request**:
```bash
curl -X GET "http://localhost/api/v1/weather?city=Jakarta"
```

**Example Response** (200 OK):
```json
{
  "success": true,
  "code": 200,
  "message": "Weather data retrieved successfully",
  "data": {
    "id": 1,
    "province_name": "DKI Jakarta",
    "city_name": "Jakarta",
    "temperature": 28.5,
    "humidity": 75,
    "wind_speed": 12.5,
    "wind_direction": "Barat",
    "rainfall": 5.2,
    "weather_condition": "Berawan",
    "visibility": 10000,
    "pressure": 1013.25,
    "timestamp": "2025-06-20 14:30:00",
    "created_at": "2025-06-20 14:30:00",
    "updated_at": "2025-06-20 14:30:00"
  },
  "timestamp": "2025-06-20 14:35:00"
}
```

**Error Response** (404 Not Found):
```json
{
  "success": false,
  "code": 404,
  "message": "Weather data not found for city: Bandung",
  "data": null,
  "timestamp": "2025-06-20 14:35:00"
}
```

**Response Codes**:
- `200 OK`: Data berhasil diambil
- `400 Bad Request`: Parameter tidak valid
- `404 Not Found`: Data tidak ditemukan
- `500 Internal Server Error`: Kesalahan server

---

### 2.2 GET Weather Forecast Data

**Endpoint**: `GET /api/v1/weather`

**Parameters**:
- `type` (string, required): Set ke "forecast"
- `city` (string, required): Nama kota
- `days` (integer, optional): Jumlah hari forecast (1-10, default: 5)

**Example Request**:
```bash
curl -X GET "http://localhost/api/v1/weather?type=forecast&city=Jakarta&days=7"
```

**Example Response** (200 OK):
```json
{
  "success": true,
  "code": 200,
  "message": "Forecast data retrieved successfully",
  "data": {
    "city": "Jakarta",
    "days": 7,
    "count": 14,
    "forecasts": [
      {
        "id": 1,
        "province_name": "DKI Jakarta",
        "city_name": "Jakarta",
        "forecast_date": "2025-06-21",
        "forecast_time": "06:00:00",
        "temperature_min": 24.5,
        "temperature_max": 31.2,
        "weather_condition": "Hujan Ringan",
        "rainfall_probability": 60,
        "wind_speed": 10.5,
        "created_at": "2025-06-20 14:00:00"
      },
      {
        "id": 2,
        "province_name": "DKI Jakarta",
        "city_name": "Jakarta",
        "forecast_date": "2025-06-21",
        "forecast_time": "18:00:00",
        "temperature_min": 23.5,
        "temperature_max": 28.0,
        "weather_condition": "Berawan",
        "rainfall_probability": 40,
        "wind_speed": 8.2,
        "created_at": "2025-06-20 14:00:00"
      }
    ]
  },
  "timestamp": "2025-06-20 14:35:00"
}
```

---

### 2.3 GET Multiple Cities Weather Data

**Endpoint**: `GET /api/v1/weather`

**Parameters**:
- `cities` (string, required): Comma-separated list of cities

**Example Request**:
```bash
curl -X GET "http://localhost/api/v1/weather?cities=Jakarta,Surabaya,Bandung"
```

**Example Response** (200 OK):
```json
{
  "success": true,
  "code": 200,
  "message": "Multiple cities weather data retrieved",
  "data": {
    "requested_cities": 3,
    "found_cities": 3,
    "data": [
      {
        "id": 1,
        "province_name": "DKI Jakarta",
        "city_name": "Jakarta",
        "temperature": 28.5,
        "humidity": 75,
        "wind_speed": 12.5,
        "weather_condition": "Berawan"
      },
      {
        "id": 2,
        "province_name": "Jawa Timur",
        "city_name": "Surabaya",
        "temperature": 29.8,
        "humidity": 70,
        "wind_speed": 11.3,
        "weather_condition": "Cerah"
      },
      {
        "id": 3,
        "province_name": "Jawa Barat",
        "city_name": "Bandung",
        "temperature": 22.5,
        "humidity": 85,
        "wind_speed": 8.5,
        "weather_condition": "Hujan Ringan"
      }
    ]
  },
  "timestamp": "2025-06-20 14:35:00"
}
```

---

### 2.4 POST Save Weather Data

**Endpoint**: `POST /api/v1/weather`

**Parameters** (JSON Body):
- `province_name` (string, required): Nama provinsi
- `city_name` (string, required): Nama kota
- `temperature` (decimal, required): Suhu dalam Celsius (-50 hingga 60)
- `humidity` (integer, required): Kelembaban (0-100%)
- `wind_speed` (decimal, optional): Kecepatan angin
- `wind_direction` (string, optional): Arah angin
- `rainfall` (decimal, optional): Curah hujan
- `weather_condition` (string, optional): Kondisi cuaca
- `visibility` (integer, optional): Jarak pandang
- `pressure` (decimal, optional): Tekanan udara

**Example Request**:
```bash
curl -X POST "http://localhost/api/v1/weather" \
  -H "Content-Type: application/json" \
  -d '{
    "province_name": "DKI Jakarta",
    "city_name": "Jakarta",
    "temperature": 28.5,
    "humidity": 75,
    "wind_speed": 12.5,
    "wind_direction": "Barat Laut",
    "rainfall": 5.2,
    "weather_condition": "Berawan",
    "visibility": 10000,
    "pressure": 1013.25
  }'
```

**Example Response** (201 Created):
```json
{
  "success": true,
  "code": 201,
  "message": "Weather data saved successfully",
  "data": {
    "id": 102,
    "city": "Jakarta",
    "temperature": 28.5,
    "humidity": 75
  },
  "timestamp": "2025-06-20 14:35:00"
}
```

---

### 2.5 GET API Health Status

**Endpoint**: `GET /api/v1/health`

**Parameters**: None

**Example Request**:
```bash
curl -X GET "http://localhost/api/v1/health"
```

**Example Response** (200 OK):
```json
{
  "status": "OK",
  "service": "Weather API BMKG",
  "version": "1.0.0",
  "timestamp": "2025-06-20 14:35:00"
}
```

---

## 3. ERROR HANDLING

### Common Error Scenarios

**Missing Required Parameter**:
```json
{
  "success": false,
  "code": 400,
  "message": "City parameter is required",
  "data": null,
  "timestamp": "2025-06-20 14:35:00"
}
```

**Invalid Input Range**:
```json
{
  "success": false,
  "code": 400,
  "message": "Temperature value out of valid range",
  "data": null,
  "timestamp": "2025-06-20 14:35:00"
}
```

**Database Connection Error**:
```json
{
  "success": false,
  "code": 500,
  "message": "Database connection error: SQLSTATE[HY000]...",
  "data": null,
  "timestamp": "2025-06-20 14:35:00"
}
```

---

## 4. TESTING DENGAN POSTMAN

### Import Collection

1. Buka Postman
2. Klik `Import`
3. Pilih `Link`
4. Paste URL atau import file JSON collection

### Example Collection JSON:
```json
{
  "info": {
    "name": "Weather API BMKG",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Get Weather Real-time",
      "request": {
        "method": "GET",
        "url": {
          "raw": "http://localhost/api/v1/weather?city=Jakarta",
          "protocol": "http",
          "host": ["localhost"],
          "path": ["api", "v1", "weather"],
          "query": [{"key": "city", "value": "Jakarta"}]
        }
      }
    },
    {
      "name": "Get Weather Forecast",
      "request": {
        "method": "GET",
        "url": {
          "raw": "http://localhost/api/v1/weather?type=forecast&city=Jakarta&days=5",
          "protocol": "http",
          "host": ["localhost"],
          "path": ["api", "v1", "weather"],
          "query": [
            {"key": "type", "value": "forecast"},
            {"key": "city", "value": "Jakarta"},
            {"key": "days", "value": "5"}
          ]
        }
      }
    },
    {
      "name": "Save Weather Data",
      "request": {
        "method": "POST",
        "header": [{"key": "Content-Type", "value": "application/json"}],
        "body": {
          "mode": "raw",
          "raw": "{\"province_name\":\"DKI Jakarta\",\"city_name\":\"Jakarta\",\"temperature\":28.5,\"humidity\":75}"
        },
        "url": {
          "raw": "http://localhost/api/v1/weather",
          "protocol": "http",
          "host": ["localhost"],
          "path": ["api", "v1", "weather"]
        }
      }
    }
  ]
}
```

---

## 5. TROUBLESHOOTING

### API Not Responding
1. Cek Apache/Nginx running: `sudo systemctl status apache2`
2. Cek PHP: `php -v`
3. Cek MySQL: `sudo systemctl status mysql`
4. Cek permission file: `ls -la /var/www/html/api/v1/`

### Database Connection Error
1. Verify credentials: `mysql -u root -p weather_bmkg_db`
2. Check database exists: `SHOW DATABASES;`
3. Check tables: `SHOW TABLES;`

### Cron Job Not Running
1. Check crontab: `crontab -l`
2. Check syslog: `sudo tail -f /var/log/syslog | grep CRON`
3. Check log file: `tail -f /var/log/weather_api_cron.log`

### Slow Response Time
1. Check database query: `EXPLAIN SELECT ...`
2. Check cache hits: Query dari api_logs table
3. Monitor server resources: `top`, `iostat`
4. Check network latency: `ping api.bmkg.go.id`

---

## 6. PERFORMANCE OPTIMIZATION TIPS

1. **Enable Query Caching**: Gunakan Redis atau Memcached
2. **Database Indexing**: Create index pada columns city_name, timestamp
3. **CDN Usage**: Cache hasil API di CDN seperti Cloudflare
4. **Gzip Compression**: Enable di Apache/Nginx untuk response compression
5. **Load Balancing**: Gunakan nginx atau HAProxy untuk distribute traffic
6. **Database Replication**: Setup master-slave MySQL untuk read scaling

---

## 7. SECURITY BEST PRACTICES

1. **SQL Injection Prevention**: Selalu gunakan prepared statements
2. **Input Validation**: Validasi semua input dari user
3. **Rate Limiting**: Implementasi throttling untuk prevent abuse
4. **HTTPS/SSL**: Gunakan SSL certificate untuk production
5. **API Key**: Implement API key authentication
6. **CORS**: Restrict CORS headers untuk production
7. **Logging**: Log semua failed attempts untuk security auditing

---

## 8. MONITORING DAN LOGGING

### Setup Monitoring
```bash
# Install monitoring tools
sudo apt-get install prometheus grafana-server

# Create prometheus config untuk scrape metrics dari API logs
# Setup Grafana dashboards untuk visualisasi
```

### Log Analysis
```bash
# Cek API logs dari database
mysql> SELECT endpoint, COUNT(*) as count, AVG(response_time) as avg_response 
        FROM api_logs 
        GROUP BY endpoint 
        ORDER BY count DESC;

# Real-time log monitoring
tail -f /var/log/weather_api_cron.log
```

---

## 9. ADDITIONAL RESOURCES

- BMKG API Documentation: https://gis.bmkg.go.id/portal/dataapi
- PHP PDO Documentation: https://www.php.net/manual/en/class.pdo.php
- RESTful API Best Practices: https://restfulapi.net/
- MySQL Performance Tuning: https://dev.mysql.com/doc/refman/8.0/en/optimization.html

---

**Document Version**: 1.0.0  
**Last Updated**: 20 June 2025  
**Author**: Development Team
