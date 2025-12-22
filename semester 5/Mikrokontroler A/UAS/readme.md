Penjelasan Cara Kerja
1. Kontrol via MQTT (Fungsi callback)

Bagian ini adalah "jembatan" antara HP dan Motor.

    Saya mengubah logika di dalam callback.

    Jika kamu mengirim angka (misalnya 2500) dari dashboard MQTT, kode akan mengubah variabel global sp (Setpoint) menjadi 2500.

    Jika kamu mengirim kata "stop" atau "off", sp akan menjadi 0.

2. Loop PID (run_pid)

Ini adalah "otak" yang menjaga kecepatan.

    Fungsi ini membandingkan RPM Target (sp) yang kamu kirim dari HP dengan RPM Asli (pv) yang dibaca sensor.

    Jika pv < sp (motor terlalu pelan), PID akan menaikkan nilai PWM (op).

    Jika pv > sp (motor terlalu cepat), PID akan menurunkan nilai PWM.

    Anti-Windup: Saya menambahkan logika untuk mencegah nilai Integral (ierr) meledak jika motor macet atau tidak kuat mencapai target.

3. Aplikasi di HP (Dashboard MQTT)

Untuk mengontrolnya, kamu bisa menggunakan aplikasi seperti IoT MQTT Panel atau MQTT Dash di Android/iOS:

    Buat koneksi ke Broker: broker.hivemq.com (Port 1883).

    Buat Widget tipe Slider atau Range.

    Set Topic ke: esp32/motor/control.

    Set Min Value: 0 dan Max Value: 5000 (atau sesuai kemampuan max RPM motor kamu).

    Saat slider digeser, motor akan mencoba mengejar RPM tersebut secara otomatis.

Catatan Penting

    Responsivitas: Kode asli kamu menghitung RPM setiap 1 detik (1000ms). Ini berarti motor butuh waktu 1 detik untuk menyadari perubahan kecepatan. Jika ingin lebih responsif, kamu bisa menurunkan interval waktu (misal ke 200ms), tapi pembacaan RPM mungkin jadi kurang stabil (fluktuatif) pada kecepatan rendah.

    Tuning PID: Nilai Kc = 0.003 mungkin perlu disesuaikan tergantung beban motor. Jika motor bergetar, kurangi Kc. Jika motor lambat mencapai target, naikkan Kc.