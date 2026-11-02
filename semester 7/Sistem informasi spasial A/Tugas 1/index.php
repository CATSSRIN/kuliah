<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surabaya GIS - Visualisasi Penduduk & Bus Stop</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin="" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>

    <!-- ===== SIDEBAR ===== -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h1>🗺️ Surabaya GIS</h1>
            <p>Visualisasi Penduduk & Bus Stop</p>
        </div>

        <div class="sidebar-content">
            <!-- Statistik Umum -->
            <div class="stats-grid">
                <div class="stat-card highlight">
                    <span class="stat-value" id="totalPenduduk">-</span>
                    <span class="stat-label">Total Penduduk</span>
                </div>
                <div class="stat-card highlight">
                    <span class="stat-value" id="totalBusStop">-</span>
                    <span class="stat-label">Total Bus Stop</span>
                </div>
                <div class="stat-card">
                    <span class="stat-value" id="totalKecamatan">-</span>
                    <span class="stat-label">Kecamatan</span>
                </div>
                <div class="stat-card">
                    <span class="stat-value" id="avgRatio">-</span>
                    <span class="stat-label">Rasio Rata-rata (penduduk/bus stop)</span>
                </div>
            </div>

            <!-- Layer Controls -->
            <div class="section-title">📂 Kontrol Layer</div>
            <div class="layer-toggles">
                <label class="layer-toggle">
                    <input type="checkbox" checked onchange="toggleKecamatanLayer(this)" />
                    <span>🏘️ Kepadatan Penduduk (Choropleth)</span>
                </label>
                <label class="layer-toggle">
                    <input type="checkbox" checked onchange="toggleBusStopLayer(this)" />
                    <span>🚌 Lokasi Bus Stop</span>
                </label>
            </div>

            <!-- Legend -->
            <div class="section-title">🎨 Legenda Populasi</div>
            <div class="legend-container">
                <div class="legend-gradient"></div>
                <div class="legend-labels">
                    <span>&lt; 40rb</span>
                    <span>80rb</span>
                    <span>120rb</span>
                    <span>160rb</span>
                    <span>&gt; 200rb</span>
                </div>
                <div style="margin-top: 10px;">
                    <div class="legend-item">
                        <span class="legend-icon" style="background: #1a73e8;"></span>
                        <span>Bus Stop (OpenStreetMap)</span>
                    </div>
                </div>
            </div>

            <!-- Top 5 Kecamatan -->
            <div class="section-title">🏆 Top 5 Kecamatan Terpadat</div>
            <div id="topKecamatan" style="margin-bottom: 16px;">
                <div class="legend-item"><span style="color:#80868b;">Memuat data...</span></div>
            </div>

            <!-- Data Table -->
            <div class="section-title">📊 Tabel Perbandingan</div>
            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Kecamatan</th>
                            <th>Penduduk</th>
                            <th>Bus Stop</th>
                            <th>Rasio</th>
                        </tr>
                    </thead>
                    <tbody id="dataTableBody">
                        <tr>
                            <td colspan="4" style="text-align:center; color:#80868b; padding:20px;">
                                Memuat data...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="sidebar-footer">
            Data penduduk: <a href="https://surabayakota.bps.go.id" target="_blank">BPS Kota Surabaya</a> (2023)<br>
            Data bus stop & batas wilayah: <a href="https://www.openstreetmap.org" target="_blank">OpenStreetMap</a> via Overpass API<br>
            Tugas 1 — Sistem Informasi Spasial A
        </div>
    </div>

    <!-- ===== MAP ===== -->
    <div class="map-container">
        <div id="map"></div>

        <!-- Info Box (muncul saat klik kecamatan) -->
        <div id="infoBox" class="info-box">
            <button class="close-btn" onclick="closeInfoBox()">✕</button>
            <h3 id="infoTitle">Kecamatan</h3>
            <div class="info-row">
                <span class="label">Penduduk</span>
                <span class="value" id="infoPenduduk">-</span>
            </div>
            <div class="info-row">
                <span class="label">Luas Wilayah</span>
                <span class="value" id="infoLuas">-</span>
            </div>
            <div class="info-row">
                <span class="label">Kepadatan</span>
                <span class="value" id="infoDensity">-</span>
            </div>
            <div class="info-row">
                <span class="label">Bus Stop</span>
                <span class="value" id="infoBusStop">-</span>
            </div>
            <div class="info-row">
                <span class="label">Rasio</span>
                <span class="value" id="infoRatio">-</span>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="loading-overlay">
            <div class="spinner"></div>
            <div class="loading-text">Memuat Peta Surabaya</div>
            <div class="loading-step" id="loadingStep">Menginisialisasi...</div>
        </div>
    </div>

    <!-- ===== SCRIPTS ===== -->
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>

    <!-- osmtogeojson (konversi Overpass API response ke GeoJSON) -->
    <script src="https://unpkg.com/osmtogeojson@3.0.0-beta.5/osmtogeojson.js"></script>

    <!-- Aplikasi utama -->
    <script src="js/app.js"></script>
</body>
</html>

