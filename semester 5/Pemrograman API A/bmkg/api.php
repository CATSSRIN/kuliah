<?php
// api.php - Versi Perbaikan dengan cURL
include 'koneksi.php';

// URL API BMKG (DKI Jakarta)
$url = "https://data.bmkg.go.id/DataMKG/MEWS/DigitalForecast/DigitalForecast-DKIJakarta.xml";

echo "<h3>Mengambil data dari BMKG...</h3>";

// --- FUNGSI BARU: Mengambil data menggunakan cURL agar tidak diblokir ---
function ambilDataURL($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // Kita menyamar sebagai Browser agar tidak ditolak server
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Ikuti jika ada redirect
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Abaikan sertifikat SSL (opsional, untuk localhost)
    
    $data = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($http_code != 200) {
        return ["status" => false, "msg" => "Gagal koneksi. HTTP Code: $http_code. Error: $error"];
    }
    return ["status" => true, "data" => $data];
}

// 1. Eksekusi Pengambilan Data
$response = ambilDataURL($url);

if (!$response['status']) {
    die("Error: " . $response['msg']);
}

$xml_string = $response['data'];

// 2. Cek apakah data yang diterima benar-benar XML
libxml_use_internal_errors(true); // Matikan error warning PHP standar agar bisa kita tangani sendiri
$xml = simplexml_load_string($xml_string);

if ($xml === false) {
    echo "<strong>Gagal membaca XML. Server mungkin mengembalikan HTML.</strong><br>";
    echo "Error XML:<br>";
    foreach(libxml_get_errors() as $error) {
        echo "- " . $error->message . "<br>";
    }
    // Debugging: Tampilkan sedikit isi data yang diterima
    echo "<br>Isi data yang diterima (500 karakter pertama):<br>";
    echo "<textarea style='width:100%; height:150px;'>" . htmlspecialchars(substr($xml_string, 0, 1000)) . "</textarea>";
    die();
}

// 3. Menyiapkan Query SQL
$stmt = $conn->prepare("INSERT INTO prakiraan_cuaca (area_id, nama_kota, waktu, kode_cuaca, suhu) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE kode_cuaca=VALUES(kode_cuaca), suhu=VALUES(suhu)");

$counter = 0;

// 4. Proses Parsing XML
if (isset($xml->forecast->area)) {
    foreach ($xml->forecast->area as $area) {
        $area_id = (string)$area['id'];
        $nama_kota = (string)$area->name[0]; 

        $data_temp = [];

        foreach ($area->parameter as $param) {
            // Ambil Weather
            if ((string)$param['id'] == 'weather') {
                foreach ($param->timerange as $timerange) {
                    $datetime = (string)$timerange['datetime'];
                    $formatted_time = DateTime::createFromFormat('YmdHi', $datetime)->format('Y-m-d H:i:s');
                    $val = (int)$timerange->value;
                    $data_temp[$formatted_time]['weather'] = $val;
                }
            }
            // Ambil Suhu
            if ((string)$param['id'] == 't') {
                foreach ($param->timerange as $timerange) {
                    $datetime = (string)$timerange['datetime'];
                    $formatted_time = DateTime::createFromFormat('YmdHi', $datetime)->format('Y-m-d H:i:s');
                    $val = (int)$timerange->value;
                    $data_temp[$formatted_time]['temp'] = $val;
                }
            }
        }

        // Simpan ke Database
        foreach ($data_temp as $waktu => $nilai) {
            if (isset($nilai['weather']) && isset($nilai['temp'])) {
                $kode_cuaca = $nilai['weather'];
                $suhu = $nilai['temp'];

                $stmt->bind_param("sssii", $area_id, $nama_kota, $waktu, $kode_cuaca, $suhu);
                if ($stmt->execute()) {
                    $counter++;
                }
            }
        }
    }
} else {
    die("Struktur XML tidak sesuai harapan.");
}

echo "<hr><h4>Sukses! $counter data cuaca telah diproses/diupdate.</h4>";
echo "<a href='index.php'>Kembali ke Halaman Utama</a>";
?>