#!/bin/bash
# Cron Job Configuration untuk RESTful API Cuaca BMKG
# File: cron_config.sh
# Letakkan di: /usr/local/bin/weather_api_cron.sh
# Buat executable: chmod +x /usr/local/bin/weather_api_cron.sh

# Konfigurasi
API_URL="http://localhost/api/v1/weather"
PHP_PATH="/usr/bin/php"
LOG_PATH="/var/log/weather_api_cron.log"
ERROR_LOG_PATH="/var/log/weather_api_cron_error.log"
SCRIPT_PATH="/var/www/html/api"

# Fungsi untuk log
log_message() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" >> "$LOG_PATH"
}

# Fungsi untuk error log
error_message() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] ERROR: $1" >> "$ERROR_LOG_PATH"
}

# Main Cron Job
main() {
    log_message "Starting Weather API Cron Job"
    
    # Array kota untuk fetch data
    CITIES=("Jakarta" "Surabaya" "Bandung" "Medan" "Semarang" "Makassar" "Palembang" "Yogyakarta" "Manado" "Denpasar")
    
    for CITY in "${CITIES[@]}"; do
        log_message "Fetching weather data for: $CITY"
        
        # Fetch dari BMKG API dan simpan ke database
        RESPONSE=$(curl -s -w "\n%{http_code}" "$API_URL?city=$CITY")
        
        # Split response dan http code
        BODY=$(echo "$RESPONSE" | head -n -1)
        HTTP_CODE=$(echo "$RESPONSE" | tail -n 1)
        
        if [ "$HTTP_CODE" -eq 200 ]; then
            log_message "Successfully fetched data for $CITY (HTTP $HTTP_CODE)"
        else
            error_message "Failed to fetch data for $CITY (HTTP $HTTP_CODE)"
        fi
    done
    
    log_message "Weather API Cron Job Completed"
}

# Run main function
main

exit 0

# ============================================
# INSTALASI CRON JOB
# ============================================
# 
# 1. Buat script ini executable:
#    chmod +x /usr/local/bin/weather_api_cron.sh
#
# 2. Buat log files:
#    sudo touch /var/log/weather_api_cron.log
#    sudo touch /var/log/weather_api_cron_error.log
#    sudo chmod 666 /var/log/weather_api_cron.log
#    sudo chmod 666 /var/log/weather_api_cron_error.log
#
# 3. Tambahkan ke crontab:
#    crontab -e
#
# 4. Gunakan salah satu schedule di bawah:
#    
#    # Jalankan setiap 30 menit
#    */30 * * * * /usr/local/bin/weather_api_cron.sh
#
#    # Jalankan setiap jam
#    0 * * * * /usr/local/bin/weather_api_cron.sh
#
#    # Jalankan setiap 6 jam
#    0 */6 * * * /usr/local/bin/weather_api_cron.sh
#
#    # Jalankan setiap hari jam 00:00
#    0 0 * * * /usr/local/bin/weather_api_cron.sh
#
# 5. Verifikasi crontab:
#    crontab -l
#
# 6. Monitor log:
#    tail -f /var/log/weather_api_cron.log
#    tail -f /var/log/weather_api_cron_error.log
#
# ============================================
