# Crontab Setup Instructions

## Setup Cron Job untuk Fetch Data

### 1. Buka Crontab Editor
```bash
crontab -e
```

### 2. Tambahkan Cron Jobs

```bash
# Fetch data setiap 5 menit
*/5 * * * * /usr/bin/php /path/to/your/project/cronjobs/fetch_all_exchanges.php >> /path/to/your/project/logs/cron_output.log 2>&1

# Cleanup data lama setiap hari jam 2 pagi
0 2 * * * /usr/bin/php /path/to/your/project/cronjobs/cleanup_old_data.php >> /path/to/your/project/logs/cleanup.log 2>&1

# Backup database setiap hari jam 3 pagi
0 3 * * * /usr/bin/mysqldump -u root -p'password' crypto_aggregator > /path/to/backups/crypto_aggregator_$(date +\%Y\%m\%d).sql
```

### 3. Verifikasi Cron Jobs
```bash
crontab -l
```

### 4. Monitor Logs
```bash
tail -f /path/to/your/project/logs/cron_output.log
```

## Penjelasan Schedule

- `*/5 * * * *` - Setiap 5 menit
- `*/15 * * * *` - Setiap 15 menit
- `0 * * * *` - Setiap jam (menit ke-0)
- `0 */6 * * *` - Setiap 6 jam
- `0 2 * * *` - Setiap hari jam 2 pagi
- `0 0 * * 0` - Setiap minggu (Minggu jam 00:00)

## Testing Cron Job Manually

```bash
php /path/to/your/project/cronjobs/fetch_all_exchanges.php
```

## Troubleshooting

### Jika cron tidak berjalan:
1. Periksa permissions file PHP:
   ```bash
   chmod +x /path/to/your/project/cronjobs/fetch_all_exchanges.php
   ```

2. Periksa log cron system:
   ```bash
   grep CRON /var/log/syslog
   ```

3. Pastikan path PHP benar:
   ```bash
   which php
   ```

### Monitoring Performance
```bash
# Lihat waktu eksekusi
grep "Duration" /path/to/your/project/logs/cron_*.log

# Lihat error
grep "Error" /path/to/your/project/logs/cron_*.log
```