<?php
/**
 * Overpass API Proxy
 * 
 * Proxy server untuk mengakses Overpass API (OpenStreetMap)
 * dari frontend tanpa masalah CORS.
 * 
 * Mendukung caching untuk mengurangi beban pada Overpass API.
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// Konfigurasi Mirror Overpass API
$OVERPASS_MIRRORS = [
    'https://overpass-api.de/api/interpreter',
    'https://lz4.overpass-api.de/api/interpreter',
    'https://overpass.kumi.systems/api/interpreter'
];
$CACHE_DIR = __DIR__ . '/../cache/';
$CACHE_TTL = 86400; // Cache berlaku 24 jam (dalam detik)

// Pastikan direktori cache ada
if (!is_dir($CACHE_DIR)) {
    mkdir($CACHE_DIR, 0755, true);
}

// Ambil parameter query
$type = isset($_GET['type']) ? $_GET['type'] : '';

if (empty($type)) {
    http_response_code(400);
    echo json_encode(['error' => 'Parameter "type" diperlukan. Gunakan: busstop atau kecamatan']);
    exit;
}

// Bounding box Surabaya: south, west, north, east
// Koordinat: -7.37, 112.60, -7.15, 112.85
$bbox = '-7.37,112.60,-7.15,112.85';

// Tentukan query Overpass berdasarkan tipe
switch ($type) {
    case 'busstop':
        $query = '
            [out:json][timeout:60];
            (
                node["highway"="bus_stop"](' . $bbox . ');
                node["amenity"="bus_station"](' . $bbox . ');
                node["public_transport"="platform"]["bus"="yes"](' . $bbox . ');
                node["public_transport"="stop_position"]["bus"="yes"](' . $bbox . ');
                node["public_transport"="platform"](' . $bbox . ');
            );
            out body;
        ';
        break;
    
    case 'kecamatan':
        $query = '
            [out:json][timeout:60];
            (
                rel["admin_level"="6"]["boundary"="administrative"](' . $bbox . ');
            );
            out geom;
        ';
        break;
    
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Tipe query tidak valid. Gunakan: busstop atau kecamatan']);
        exit;
}

// Cek cache
$cacheFile = $CACHE_DIR . $type . '_cache.json';
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $CACHE_TTL) {
    // Gunakan data dari cache
    $cachedData = file_get_contents($cacheFile);
    echo $cachedData;
    exit;
}

// Fetch dari Overpass API (dengan fallback mirror)
$postData = 'data=' . urlencode($query);
$response = false;
$httpCode = 0;
$curlError = '';

foreach ($OVERPASS_MIRRORS as $mirrorUrl) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $mirrorUrl,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 45,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_USERAGENT => 'SurabayaGIS-Prototype/1.0',
        CURLOPT_HTTPHEADER => ['Accept: application/json']
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($response !== false && $httpCode === 200 && strlen($response) > 200) {
        break; // Berhasil mendapatkan data yang valid
    }
}

if ($response === false || $httpCode !== 200) {
    http_response_code(502);
    echo json_encode([
        'error' => 'Gagal mengambil data dari Overpass API mirror',
        'http_code' => $httpCode,
        'curl_error' => $curlError
    ]);
    exit;
}

// Simpan ke cache
file_put_contents($cacheFile, $response);

echo $response;
