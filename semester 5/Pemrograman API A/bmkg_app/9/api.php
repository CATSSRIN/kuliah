<?php
// api.php - Versi JSON untuk AJAX
header('Content-Type: application/json'); // Memberitahu browser bahwa ini data JSON
include 'koneksi.php';

// URL API BMKG Surabaya
$url = "https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=35.78.07.1002";

function sendResponse($status, $message) {
    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}

// Fungsi CURL
function ambilDataAPI($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $data = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code != 200) {
        return false;
    }
    return $data;
}

$data_api = ambilDataAPI($url);

if (!$data_api) {
    sendResponse('error', 'Gagal menghubungi server BMKG.');
}

$json_data = json_decode($data_api, true);

if ($json_data === null) {
    sendResponse('error', 'Format data dari BMKG tidak valid.');
}

// Persiapan Query
$stmt = $conn->prepare("INSERT INTO prakiraan_cuaca (kecamatan, desa, waktu, suhu, cuaca) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE suhu=VALUES(suhu), cuaca=VALUES(cuaca)");

$counter = 0;

if (isset($json_data['data'][0])) {
    $lokasi = $json_data['data'][0]['lokasi'];
    $nama_kecamatan = $lokasi['kecamatan'];
    $nama_desa = $lokasi['desa'];
    
    $list_cuaca = $json_data['data'][0]['cuaca'];

    foreach ($list_cuaca as $group_cuaca) {
        foreach ($group_cuaca as $row) {
            $waktu_local = $row['local_datetime'];
            $suhu = $row['t'];
            $deskripsi_cuaca = $row['weather_desc'];
            
            $stmt->bind_param("sssis", $nama_kecamatan, $nama_desa, $waktu_local, $suhu, $deskripsi_cuaca);
            
            if ($stmt->execute()) {
                $counter++;
            }
        }
    }
    sendResponse('success', "Berhasil memperbarui $counter data cuaca!");
} else {
    sendResponse('error', 'Struktur data JSON tidak sesuai.');
}
?>