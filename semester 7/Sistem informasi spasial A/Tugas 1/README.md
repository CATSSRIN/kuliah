# 🗺️ Surabaya GIS - Visualisasi Penduduk & Bus Stop

Prototype website GIS (Geographic Information System) yang memvisualisasikan perbandingan antara **jumlah penduduk per kecamatan** dengan **lokasi bus stop** di Kota Surabaya menggunakan **peta interaktif OpenStreetMap** dan PHP.

> **Tugas 1 — Sistem Informasi Spasial A**

---

## 📸 Fitur Utama

- **Choropleth Map**: Pewarnaan 31 kecamatan berdasarkan jumlah penduduk (semakin pekat = semakin padat).
- **Bus Stop Markers**: Penanda lokasi 1.000+ bus stop / halte di seluruh Kota Surabaya dari OpenStreetMap.
- **Analisis Rasio Ketercukupan**: Perhitungan rasio perbandingan jumlah penduduk terhadap jumlah bus stop per kecamatan.
- **Interaktivitas Penuh**: Setiap poligon kecamatan dan marker bus stop dapat diklik untuk membuka popup dan panel informasi detail.
- **Tabel & Filter Real-Time**: Daftar perbandingan seluruh kecamatan dengan fitur klik untuk langsung zoom/fokus ke kecamatan terkait di peta.
- **Layer Control**: Opsi mematikan/menyalakan layer choropleth atau bus stop.
- **Overpass Proxy dengan Multi-Mirror & Caching**: Pengambilan data OSM secara andal dengan fallback mirror dan cache lokal server.

---

## 🏗️ Arsitektur Sistem

```
┌──────────────────────────────────────────────────────────────┐
│                        FRONTEND                              │
│  ┌─────────┐  ┌──────────────┐  ┌─────────────────────────┐ │
│  │ Leaflet │  │ osmtogeojson │  │       app.js            │ │
│  │   Map   │  │  (converter) │  │  (logika visualisasi)   │ │
│  └────┬────┘  └──────┬───────┘  └──────────┬──────────────┘ │
│       │              │                     │                 │
└───────┼──────────────┼─────────────────────┼─────────────────┘
        │              │                     │
        ▼              ▼                     ▼
┌───────────────────────────────────────────────────────────────┐
│                      BACKEND (PHP)                            │
│  ┌──────────────────┐        ┌──────────────────────────┐    │
│  │  penduduk.php    │        │  overpass_proxy.php      │    │
│  │  (data BPS)      │        │  (proxy + cache)         │    │
│  └──────────────────┘        └────────────┬─────────────┘    │
└───────────────────────────────────────────┼──────────────────┘
                                            │
                                            ▼
                              ┌─────────────────────────┐
                              │    Overpass API          │
                              │  (OpenStreetMap Data)    │
                              │  • overpass-api.de       │
                              │  • lz4.overpass-api.de   │
                              │  • overpass.kumi.systems │
                              └─────────────────────────┘
```

---

## 📊 Sumber Data & Format API

Data yang digunakan berasal dari 2 sumber utama:
1. **Badan Pusat Statistik (BPS) Kota Surabaya** untuk data kependudukan dan luas wilayah kecamatan.
2. **OpenStreetMap (OSM) via Overpass API** untuk data koordinat halte (bus stop) dan batas wilayah poligon spasial kecamatan.

---

### 1. Data Kependudukan (`api/penduduk.php`)
- **Sumber**: Publikasi Resmi BPS Kota Surabaya (Proyeksi 2023)
- **Format Response**: JSON

#### Contoh Format JSON Mentah dari API:
```json
{
  "sumber": "BPS Kota Surabaya - Proyeksi Penduduk 2023",
  "tahun": 2023,
  "satuan_penduduk": "jiwa",
  "satuan_luas": "km²",
  "kota": "Surabaya",
  "total_penduduk": 2972801,
  "total_luas": 335.28,
  "jumlah_kecamatan": 31,
  "kecamatan": [
    {
      "nama": "Mulyorejo",
      "penduduk": 85270,
      "luas": 14.21,
      "kelurahan": 6,
      "lat": -7.2678,
      "lon": 112.7903
    },
    {
      "nama": "Gubeng",
      "penduduk": 139210,
      "luas": 7.99,
      "kelurahan": 6,
      "lat": -7.2778,
      "lon": 112.7561
    }
  ]
}
```

---

### 2. Data Lokasi Bus Stop (`api/overpass_proxy.php?type=busstop`)
- **Sumber**: OpenStreetMap via Overpass API
- **Bounding Box Surabaya**: `[-7.37, 112.60, -7.15, 112.85]`
- **Filter Tag**: `node["highway"="bus_stop"]`, `node["amenity"="bus_station"]`, `node["public_transport"="platform"]`

#### Contoh Format JSON Mentah dari API:
```json
{
  "version": 0.6,
  "generator": "Overpass API",
  "elements": [
    {
      "type": "node",
      "id": 1084291823,
      "lat": -7.2694125,
      "lon": 112.7912401,
      "tags": {
        "highway": "bus_stop",
        "name": "Halte Manyar Sabrangan",
        "operator": "Trans Semanggi Suroboyo",
        "network": "Teman Bus",
        "route_ref": "K2L",
        "shelter": "yes",
        "public_transport": "platform"
      }
    },
    {
      "type": "node",
      "id": 1084291850,
      "lat": -7.2785120,
      "lon": 112.7554310,
      "tags": {
        "highway": "bus_stop",
        "name": "Halte RS Husada Utama",
        "operator": "Suroboyo Bus",
        "network": "Trans Semanggi",
        "shelter": "yes"
      }
    }
  ]
}
```

---

### 3. Data Batas Spasial Wilayah Kecamatan (`api/overpass_proxy.php?type=kecamatan`)
- **Sumber**: OpenStreetMap via Overpass API
- **Admin Level di Surabaya**: `rel["admin_level"="6"]["boundary"="administrative"]`
  > *Catatan Spasial*: Di Kota Surabaya pada OSM, relasi `admin_level = 6` adalah batas resmi 31 Kecamatan (sedangkan `admin_level = 7` adalah kelurahan seperti Manyar Sabrangan, Ketabang, dsb).

#### Contoh Format JSON Mentah dari API:
```json
{
  "version": 0.6,
  "generator": "Overpass API",
  "elements": [
    {
      "type": "relation",
      "id": 8225259,
      "bounds": {
        "minlat": -7.2882,
        "minlon": 112.7725,
        "maxlat": -7.2513,
        "maxlon": 112.8122
      },
      "tags": {
        "admin_level": "6",
        "boundary": "administrative",
        "name": "Mulyorejo",
        "type": "boundary"
      },
      "members": [
        {
          "type": "way",
          "ref": 578291023,
          "role": "outer",
          "geometry": [
            {"lat": -7.2882, "lon": 112.7725},
            {"lat": -7.2875, "lon": 112.7741}
          ]
        }
      ]
    }
  ]
}
```

---

## 🗄️ Rancangan Skema Basis Data MySQL (Spatial Database)

Jika seluruh data JSON mentah dari API di atas disimpan dan diolah ke dalam basis data relasional **MySQL** (dengan ekstensi Spatial GIS `POINT` dan `POLYGON`), berikut rancangan struktur tabel dan pemetaan kolomnya:

```
┌─────────────────────────┐          ┌───────────────────────────┐
│   wilayah_kecamatan     │ 1      N │      data_penduduk        │
├─────────────────────────┼──────────┼───────────────────────────┤
│ PK  id_kecamatan        │          │ PK  id_penduduk           │
│     nama_kecamatan      │          │ FK  id_kecamatan          │
│     luas_wilayah_km2    │          │     tahun                 │
│     jumlah_kelurahan    │          │     jumlah_jiwa           │
│     latitude_pusat      │          │     kepadatan_jiwa_km2    │
│     longitude_pusat     │          └───────────────────────────┘
│     batas_polygon       │ 1
└───────────┬─────────────┘
            │
            │ 1
            │
            │ N
┌───────────▼─────────────┐
│       bus_stop          │
├─────────────────────────┤
│ PK  id_osm_bus_stop     │
│ FK  id_kecamatan        │
│     nama_halte          │
│     operator            │
│     jaringan_trayek     │
│     memiliki_shelter    │
│     latitude            │
│     longitude           │
│     titik_koordinat     │
│     waktu_sinkronisasi  │
└─────────────────────────┘
```

---

### DDL (Data Definition Language) MySQL

```sql
-- 1. Buat Database
CREATE DATABASE IF NOT EXISTS db_surabaya_gis CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_surabaya_gis;

-- 2. Tabel Wilayah Kecamatan
CREATE TABLE IF NOT EXISTS wilayah_kecamatan (
    id_kecamatan INT AUTO_INCREMENT PRIMARY KEY,
    osm_relation_id BIGINT UNIQUE COMMENT 'ID Relasi OSM jika ada',
    nama_kecamatan VARCHAR(100) NOT NULL UNIQUE,
    luas_wilayah_km2 DECIMAL(6, 2) NOT NULL COMMENT 'Luas dari BPS dalam km²',
    jumlah_kelurahan INT DEFAULT 0 COMMENT 'Jumlah desa/kelurahan',
    latitude_pusat DECIMAL(9, 6) NOT NULL COMMENT 'Koordinat centroid lat',
    longitude_pusat DECIMAL(9, 6) NOT NULL COMMENT 'Koordinat centroid lon',
    titik_pusat_geom POINT NOT NULL SRID 4326 COMMENT 'Objek Spatial Centroid',
    batas_polygon MULTIPOLYGON NULL SRID 4326 COMMENT 'Batas spasial wilayah',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    SPATIAL INDEX(titik_pusat_geom),
    SPATIAL INDEX(batas_polygon)
) ENGINE=InnoDB;

-- 3. Tabel Data Penduduk Tahunan
CREATE TABLE IF NOT EXISTS data_penduduk (
    id_penduduk INT AUTO_INCREMENT PRIMARY KEY,
    id_kecamatan INT NOT NULL,
    tahun YEAR NOT NULL COMMENT 'Tahun data sensus / proyeksi',
    jumlah_jiwa INT NOT NULL COMMENT 'Total populasi jiwa',
    kepadatan_jiwa_km2 DECIMAL(10, 2) GENERATED ALWAYS AS (jumlah_jiwa / NULLIF(
        (SELECT luas_wilayah_km2 FROM wilayah_kecamatan WHERE wilayah_kecamatan.id_kecamatan = data_penduduk.id_kecamatan), 0)
    ) VIRTUAL COMMENT 'Kepadatan penduduk per km²',
    sumber_data VARCHAR(150) DEFAULT 'BPS Kota Surabaya',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kecamatan) REFERENCES wilayah_kecamatan(id_kecamatan) ON DELETE CASCADE,
    UNIQUE KEY uq_kecamatan_tahun (id_kecamatan, tahun)
) ENGINE=InnoDB;

-- 4. Tabel Bus Stop / Halte
CREATE TABLE IF NOT EXISTS bus_stop (
    id_osm_bus_stop BIGINT PRIMARY KEY COMMENT 'ID node dari OpenStreetMap',
    id_kecamatan INT NULL COMMENT 'Relasi kecamatan tempat halte berada',
    nama_halte VARCHAR(150) NOT NULL COMMENT 'Atribut name halte',
    operator VARCHAR(100) DEFAULT '-' COMMENT 'Operator armada (misal Suroboyo Bus)',
    jaringan_trayek VARCHAR(100) DEFAULT '-' COMMENT 'Jaringan rute / network',
    kode_rute VARCHAR(50) DEFAULT '-' COMMENT 'Atribut route_ref',
    memiliki_shelter ENUM('yes', 'no', 'unknown') DEFAULT 'unknown' COMMENT 'Ketersediaan shelter',
    latitude DECIMAL(9, 6) NOT NULL,
    longitude DECIMAL(9, 6) NOT NULL,
    titik_koordinat POINT NOT NULL SRID 4326 COMMENT 'Objek Spasial Point Halte',
    waktu_sinkronisasi DATETIME NOT NULL COMMENT 'Waktu data di-fetch dari Overpass',
    FOREIGN KEY (id_kecamatan) REFERENCES wilayah_kecamatan(id_kecamatan) ON DELETE SET NULL,
    SPATIAL INDEX(titik_koordinat)
) ENGINE=InnoDB;

-- 5. View Agregasi Analisis Rasio Spasial
CREATE OR REPLACE VIEW view_analisis_rasio AS
SELECT 
    k.id_kecamatan,
    k.nama_kecamatan,
    k.luas_wilayah_km2,
    p.jumlah_jiwa AS total_penduduk,
    ROUND(p.jumlah_jiwa / k.luas_wilayah_km2, 1) AS kepadatan_per_km2,
    COUNT(b.id_osm_bus_stop) AS total_bus_stop,
    ROUND(p.jumlah_jiwa / NULLIF(COUNT(b.id_osm_bus_stop), 0)) AS rasio_penduduk_per_halte,
    CASE 
        WHEN COUNT(b.id_osm_bus_stop) = 0 THEN 'Sangat Kritis (0 Halte)'
        WHEN (p.jumlah_jiwa / COUNT(b.id_osm_bus_stop)) < 20000 THEN 'Baik (< 20.000 jiwa/halte)'
        WHEN (p.jumlah_jiwa / COUNT(b.id_osm_bus_stop)) <= 50000 THEN 'Sedang (20.000 - 50.000)'
        ELSE 'Kurang (> 50.000 jiwa/halte)'
    END AS status_ketercukupan
FROM wilayah_kecamatan k
LEFT JOIN data_penduduk p ON k.id_kecamatan = p.id_kecamatan AND p.tahun = 2023
LEFT JOIN bus_stop b ON k.id_kecamatan = b.id_kecamatan
GROUP BY k.id_kecamatan, k.nama_kecamatan, k.luas_wilayah_km2, p.jumlah_jiwa;
```

---

### Tabel Pemetaan Kolom (Data Dictionary & Mapping)

Berikut adalah tabel pemetaan yang menjelaskan bagaimana setiap field data mentah JSON dari API ditransformasikan ke kolom basis data MySQL:

#### A. Pemetaan Data Penduduk (`api/penduduk.php` ➔ `wilayah_kecamatan` & `data_penduduk`)

| Path Field pada JSON API | Nama Kolom di MySQL | Tipe Data MySQL | Contoh Nilai Mentah | Penjelasan & Aturan Transformasi |
| :--- | :--- | :--- | :--- | :--- |
| `kecamatan[].nama` | `wilayah_kecamatan.nama_kecamatan` | `VARCHAR(100)` | `"Mulyorejo"` | Nama resmi kecamatan Kota Surabaya (Unique Key). |
| `kecamatan[].luas` | `wilayah_kecamatan.luas_wilayah_km2` | `DECIMAL(6,2)` | `14.21` | Luas wilayah dalam kilometer persegi ($km^2$). |
| `kecamatan[].kelurahan` | `wilayah_kecamatan.jumlah_kelurahan` | `INT` | `6` | Jumlah total kelurahan yang dinaungi kecamatan. |
| `kecamatan[].lat` | `wilayah_kecamatan.latitude_pusat` | `DECIMAL(9,6)` | `-7.267800` | Garis lintang pusat kecamatan (derajat desimal). |
| `kecamatan[].lon` | `wilayah_kecamatan.longitude_pusat` | `DECIMAL(9,6)` | `112.790300`| Garis bujur pusat kecamatan (derajat desimal). |
| `lat` & `lon` | `wilayah_kecamatan.titik_pusat_geom` | `POINT` | `POINT(112.7903 -7.2678)` | Dikonversi via `ST_GeomFromText('POINT(lon lat)', 4326)`. |
| `tahun` | `data_penduduk.tahun` | `YEAR` | `2023` | Tahun sensus / proyeksi data dari BPS. |
| `kecamatan[].penduduk` | `data_penduduk.jumlah_jiwa` | `INT` | `85270` | Jumlah populasi penduduk dalam satuan jiwa. |
| `penduduk / luas` | `data_penduduk.kepadatan_jiwa_km2` | `DECIMAL(10,2)` | `6000.70` | Dihitung otomatis: $\frac{\text{jumlah\_jiwa}}{\text{luas\_wilayah}}$. |
| `sumber` | `data_penduduk.sumber_data` | `VARCHAR(150)` | `"BPS Kota Surabaya 2023"` | Sumber rujukan data kependudukan. |

---

#### B. Pemetaan Data Bus Stop (`Overpass API` ➔ `bus_stop`)

| Path Field pada JSON API | Nama Kolom di MySQL | Tipe Data MySQL | Contoh Nilai Mentah | Penjelasan & Aturan Transformasi |
| :--- | :--- | :--- | :--- | :--- |
| `elements[].id` | `bus_stop.id_osm_bus_stop` | `BIGINT` | `1084291823` | ID Node unik OpenStreetMap sebagai Primary Key. |
| `elements[].tags.name` | `bus_stop.nama_halte` | `VARCHAR(150)` | `"Halte Manyar Sabrangan"` | Nama halte. Jika kosong, default: `"Bus Stop [id]"`. |
| `elements[].tags.operator` | `bus_stop.operator` | `VARCHAR(100)` | `"Trans Semanggi Suroboyo"`| Nama penyedia armada/operator angkutan. |
| `elements[].tags.network` | `bus_stop.jaringan_trayek` | `VARCHAR(100)` | `"Teman Bus"` | Jaringan transportasi publik. |
| `elements[].tags.route_ref` | `bus_stop.kode_rute` | `VARCHAR(50)` | `"K2L"` | Nomor trayek atau kode rute bus. |
| `elements[].tags.shelter` | `bus_stop.memiliki_shelter` | `ENUM('yes','no','unknown')`| `"yes"` | Status ketersediaan kanopi/shelter fisik. |
| `elements[].lat` | `bus_stop.latitude` | `DECIMAL(9,6)` | `-7.269412` | Koordinat latitude posisi halte. |
| `elements[].lon` | `bus_stop.longitude` | `DECIMAL(9,6)` | `112.791240` | Koordinat longitude posisi halte. |
| `lat` & `lon` | `bus_stop.titik_koordinat` | `POINT` | `POINT(112.791240 -7.269412)` | Objek spasial titik untuk kueri spasial `ST_Contains`. |
| Spatial Relation | `bus_stop.id_kecamatan` | `INT (FK)` | `16` (Kec. Mulyorejo) | Didapat dari kueri spasial: poligon kecamatan yang melingkupi titik halte ini. |
| System Timestamp | `bus_stop.waktu_sinkronisasi` | `DATETIME` | `2026-11-02 12:35:00` | Waktu saat data di-fetch dan disimpan ke basis data. |

---

## 🛠️ Penyelesaian Masalah Interaktivitas Kecamatan (Kasus Manyar)

### Masalah yang Ditemukan Sebelumnya:
1. Saat pengguna mengklik area **Manyar** di peta, poligon tidak merespons (tidak bisa dipencet/diklik).
2. **Penyebab Utama**:
   - Di OpenStreetMap Kota Surabaya, **Manyar Sabrangan** adalah **Kelurahan** (ditandai dengan `admin_level = 7`), sedangkan **Kecamatan resminya adalah Mulyorejo** (`admin_level = 6`).
   - Query Overpass sebelumnya mengambil `admin_level = 6` dan `7`. Akibatnya, poligon-poligon kecil kelurahan (seperti Manyar Sabrangan, Ketabang, Kapasan, Pacar Keling) ikut tergambar di peta dan menimpa poligon kecamatan.
   - Karena "Manyar Sabrangan" bukan nama kecamatan di data kependudukan BPS, sistem tidak dapat menemukan datanya (`findKecamatan("Manyar Sabrangan") == null`). Akibatnya, event klik dan popup tidak terpasang pada layer tersebut.

### Solusi yang Diterapkan:
1. **Query Dikhususkan ke `admin_level = 6`**:
   - Query Overpass di `api/overpass_proxy.php` diperbarui untuk hanya mengambil relasi tingkat kecamatan resmi (`admin_level = 6`). Tepat terdapat **31 kecamatan** di Kota Surabaya.
2. **Normalisasi & Strict Matching**:
   - Fungsi normalisasi nama (`normalizeKecName`) membuang spasi dan karakter khusus sehingga penulisan seperti *"Karang Pilang"* di OSM 100% cocok dengan *"Karangpilang"* di BPS.
3. **Hasil**:
   - Seluruh wilayah Kota Surabaya kini terbagi rapi ke dalam **31 poligon kecamatan resmi**.
   - Area Manyar kini berada secara utuh di bawah poligon **Kecamatan Mulyorejo**. Saat area Manyar diklik, poligon langsung ter-highlight dan menampilkan info lengkap Kecamatan Mulyorejo serta menyorot baris tabel terkait.

---

## 🚀 Cara Menjalankan Aplikasi

### Prasyarat:
- **PHP 7.4+** dengan ekstensi `curl` aktif.
- Web Browser modern (Chrome, Firefox, Edge).

### Menjalankan Server:
1. Buka terminal pada folder proyek:
   ```bash
   cd "d:\Github\kuliah\semester 7\Sistem informasi spasial A\Tugas 1"
   ```
2. Jalankan PHP Built-in Server:
   ```bash
   php -S localhost:8080
   ```
3. Akses aplikasi melalui browser:
   👉 **[http://localhost:8080](http://localhost:8080)**

---

## 📁 Struktur Berkas

```
Tugas 1/
├── index.php                  # Halaman utama visualisasi (Peta Leaflet + Sidebar Statistik)
├── api/
│   ├── penduduk.php           # API data kependudukan & luas wilayah BPS Surabaya
│   └── overpass_proxy.php     # Proxy cURL ke Overpass OSM dengan multi-mirror & cache
├── js/
│   └── app.js                 # Logika GIS (Leaflet, Choropleth, Interaktivitas, Tabel)
├── css/
│   └── style.css              # Tata letak responsif & styling tema GIS
├── cache/                     # Direktori penyimpanan cache JSON (24 jam)
│   ├── busstop_cache.json     # Cache 1.049 bus stop Surabaya
│   └── kecamatan_cache.json   # Cache 31 poligon batas kecamatan
└── README.md                  # Dokumentasi teknis proyek & skema database
```
