<?php
/**
 * API Endpoint: Data Penduduk per Kecamatan - Kota Surabaya
 * Sumber Data: BPS Kota Surabaya (bps.go.id) - Proyeksi Penduduk 2023
 * 
 * Endpoint ini menyediakan data jumlah penduduk, luas wilayah,
 * dan jumlah kelurahan untuk setiap kecamatan di Kota Surabaya.
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$data = [
    "sumber" => "BPS Kota Surabaya - Proyeksi Penduduk 2023",
    "tahun" => 2023,
    "satuan_penduduk" => "jiwa",
    "satuan_luas" => "km²",
    "kota" => "Surabaya",
    "kecamatan" => [
        [
            "nama" => "Tegalsari",
            "penduduk" => 104291,
            "luas" => 4.29,
            "kelurahan" => 5,
            "lat" => -7.2756,
            "lon" => 112.7378
        ],
        [
            "nama" => "Genteng",
            "penduduk" => 60550,
            "luas" => 1.73,
            "kelurahan" => 5,
            "lat" => -7.2644,
            "lon" => 112.7461
        ],
        [
            "nama" => "Bubutan",
            "penduduk" => 103550,
            "luas" => 3.86,
            "kelurahan" => 5,
            "lat" => -7.2503,
            "lon" => 112.7281
        ],
        [
            "nama" => "Simokerto",
            "penduduk" => 100050,
            "luas" => 2.59,
            "kelurahan" => 5,
            "lat" => -7.2456,
            "lon" => 112.7536
        ],
        [
            "nama" => "Pabean Cantian",
            "penduduk" => 82440,
            "luas" => 6.80,
            "kelurahan" => 5,
            "lat" => -7.2260,
            "lon" => 112.7357
        ],
        [
            "nama" => "Semampir",
            "penduduk" => 190120,
            "luas" => 8.76,
            "kelurahan" => 5,
            "lat" => -7.2185,
            "lon" => 112.7485
        ],
        [
            "nama" => "Krembangan",
            "penduduk" => 119800,
            "luas" => 8.34,
            "kelurahan" => 5,
            "lat" => -7.2285,
            "lon" => 112.7295
        ],
        [
            "nama" => "Kenjeran",
            "penduduk" => 154670,
            "luas" => 7.77,
            "kelurahan" => 4,
            "lat" => -7.2240,
            "lon" => 112.7750
        ],
        [
            "nama" => "Bulak",
            "penduduk" => 42350,
            "luas" => 6.72,
            "kelurahan" => 4,
            "lat" => -7.2422,
            "lon" => 112.7944
        ],
        [
            "nama" => "Tambaksari",
            "penduduk" => 223530,
            "luas" => 8.99,
            "kelurahan" => 8,
            "lat" => -7.2583,
            "lon" => 112.7647
        ],
        [
            "nama" => "Gubeng",
            "penduduk" => 139210,
            "luas" => 7.99,
            "kelurahan" => 6,
            "lat" => -7.2778,
            "lon" => 112.7561
        ],
        [
            "nama" => "Rungkut",
            "penduduk" => 108760,
            "luas" => 21.08,
            "kelurahan" => 6,
            "lat" => -7.3228,
            "lon" => 112.7706
        ],
        [
            "nama" => "Tenggilis Mejoyo",
            "penduduk" => 56870,
            "luas" => 5.52,
            "kelurahan" => 5,
            "lat" => -7.3178,
            "lon" => 112.7531
        ],
        [
            "nama" => "Gunung Anyar",
            "penduduk" => 54210,
            "luas" => 9.71,
            "kelurahan" => 4,
            "lat" => -7.3417,
            "lon" => 112.7803
        ],
        [
            "nama" => "Sukolilo",
            "penduduk" => 108430,
            "luas" => 23.69,
            "kelurahan" => 7,
            "lat" => -7.2939,
            "lon" => 112.7892
        ],
        [
            "nama" => "Mulyorejo",
            "penduduk" => 85270,
            "luas" => 14.21,
            "kelurahan" => 6,
            "lat" => -7.2678,
            "lon" => 112.7903
        ],
        [
            "nama" => "Sawahan",
            "penduduk" => 207340,
            "luas" => 6.93,
            "kelurahan" => 6,
            "lat" => -7.2811,
            "lon" => 112.7222
        ],
        [
            "nama" => "Wonokromo",
            "penduduk" => 164530,
            "luas" => 8.47,
            "kelurahan" => 6,
            "lat" => -7.2989,
            "lon" => 112.7381
        ],
        [
            "nama" => "Karangpilang",
            "penduduk" => 72480,
            "luas" => 9.23,
            "kelurahan" => 4,
            "lat" => -7.3320,
            "lon" => 112.6930
        ],
        [
            "nama" => "Dukuh Pakis",
            "penduduk" => 60490,
            "luas" => 9.94,
            "kelurahan" => 4,
            "lat" => -7.2928,
            "lon" => 112.7094
        ],
        [
            "nama" => "Wiyung",
            "penduduk" => 68750,
            "luas" => 12.46,
            "kelurahan" => 4,
            "lat" => -7.3156,
            "lon" => 112.6928
        ],
        [
            "nama" => "Gayungan",
            "penduduk" => 45680,
            "luas" => 6.07,
            "kelurahan" => 4,
            "lat" => -7.3289,
            "lon" => 112.7278
        ],
        [
            "nama" => "Wonocolo",
            "penduduk" => 80340,
            "luas" => 6.77,
            "kelurahan" => 5,
            "lat" => -7.3194,
            "lon" => 112.7389
        ],
        [
            "nama" => "Jambangan",
            "penduduk" => 49210,
            "luas" => 4.19,
            "kelurahan" => 4,
            "lat" => -7.3261,
            "lon" => 112.7156
        ],
        [
            "nama" => "Tandes",
            "penduduk" => 91430,
            "luas" => 11.07,
            "kelurahan" => 6,
            "lat" => -7.2556,
            "lon" => 112.6831
        ],
        [
            "nama" => "Sukomanunggal",
            "penduduk" => 100490,
            "luas" => 9.23,
            "kelurahan" => 6,
            "lat" => -7.2694,
            "lon" => 112.7008
        ],
        [
            "nama" => "Asemrowo",
            "penduduk" => 45960,
            "luas" => 15.44,
            "kelurahan" => 3,
            "lat" => -7.2417,
            "lon" => 112.6969
        ],
        [
            "nama" => "Benowo",
            "penduduk" => 62340,
            "luas" => 26.37,
            "kelurahan" => 4,
            "lat" => -7.2333,
            "lon" => 112.6556
        ],
        [
            "nama" => "Pakal",
            "penduduk" => 50870,
            "luas" => 22.07,
            "kelurahan" => 5,
            "lat" => -7.2483,
            "lon" => 112.6261
        ],
        [
            "nama" => "Lakarsantri",
            "penduduk" => 55690,
            "luas" => 18.99,
            "kelurahan" => 6,
            "lat" => -7.2917,
            "lon" => 112.6456
        ],
        [
            "nama" => "Sambikerep",
            "penduduk" => 60510,
            "luas" => 23.68,
            "kelurahan" => 4,
            "lat" => -7.2761,
            "lon" => 112.6611
        ]
    ]
];

// Hitung total
$totalPenduduk = 0;
$totalLuas = 0;
foreach ($data['kecamatan'] as $kec) {
    $totalPenduduk += $kec['penduduk'];
    $totalLuas += $kec['luas'];
}
$data['total_penduduk'] = $totalPenduduk;
$data['total_luas'] = round($totalLuas, 2);
$data['jumlah_kecamatan'] = count($data['kecamatan']);

echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
