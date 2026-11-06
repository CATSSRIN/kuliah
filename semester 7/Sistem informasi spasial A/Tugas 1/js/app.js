/**
 * =====================================================
 * Surabaya GIS - Visualisasi Penduduk & Bus Stop
 * =====================================================
 * 
 * Aplikasi peta interaktif menggunakan Leaflet.js + OpenStreetMap
 * untuk memvisualisasikan perbandingan jumlah penduduk per kecamatan
 * dengan lokasi bus stop di Kota Surabaya.
 * 
 * Data Sources:
 * - Penduduk: BPS Kota Surabaya (via PHP API)
 * - Bus Stop: Overpass API (OpenStreetMap)
 * - Batas Kecamatan: Overpass API (OpenStreetMap)
 */

// ==================== KONFIGURASI ====================
const CONFIG = {
    center: [-7.2756, 112.7426],  // Pusat Kota Surabaya
    zoom: 12,
    minZoom: 10,
    maxZoom: 18,
    // Warna choropleth (YlOrRd)
    colors: ['#ffffcc', '#ffeda0', '#fed976', '#feb24c', '#fd8d3c', '#fc4e2a', '#e31a1c', '#b10026'],
    // Breakpoints populasi
    breaks: [40000, 60000, 80000, 100000, 130000, 160000, 200000]
};

// ==================== VARIABEL GLOBAL ====================
let map;
let kecamatanLayer = null;
let busStopLayer = null;
let pendudukData = [];
let busStopData = [];
let kecamatanGeoJSON = null;

// ==================== INISIALISASI ====================
document.addEventListener('DOMContentLoaded', function () {
    initMap();
    loadAllData();
});

/**
 * Inisialisasi peta Leaflet
 */
function initMap() {
    map = L.map('map', {
        center: CONFIG.center,
        zoom: CONFIG.zoom,
        minZoom: CONFIG.minZoom,
        maxZoom: CONFIG.maxZoom,
        zoomControl: false
    });

    // Tambahkan tile layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);

    // Zoom control di kanan
    L.control.zoom({ position: 'topright' }).addTo(map);

    // Scale control
    L.control.scale({ position: 'bottomright', imperial: false }).addTo(map);
}

/**
 * Muat semua data secara paralel dan terurut
 */
async function loadAllData() {
    updateLoading('Memuat data kependudukan BPS...');

    try {
        // 1. Muat data kependudukan dari BPS
        const pendudukResponse = await fetch('api/penduduk.php');
        if (!pendudukResponse.ok) throw new Error('Gagal memuat data penduduk');
        const pendudukResult = await pendudukResponse.json();
        pendudukData = pendudukResult.kecamatan;

        updateLoading('Memuat batas wilayah spasial kecamatan...');

        // 2. Muat batas spasial kecamatan dari Overpass API (diperlukan sebelum proses bus stop)
        try {
            const kecamatanResponse = await fetch('api/overpass_proxy.php?type=kecamatan');
            if (kecamatanResponse.ok) {
                const kecamatanResult = await kecamatanResponse.json();
                processKecamatanData(kecamatanResult);
            } else {
                console.warn('Gagal memuat batas polygon kecamatan, beralih ke fallback');
                useFallbackKecamatan();
            }
        } catch (errKec) {
            console.warn('Batas kecamatan error, beralih ke fallback:', errKec);
            useFallbackKecamatan();
        }

        updateLoading('Memuat lokasi bus stop dari OpenStreetMap...');

        // 3. Muat titik bus stop dan lakukan kalkulasi spasial Point-in-Polygon
        const busStopResponse = await fetch('api/overpass_proxy.php?type=busstop');
        if (!busStopResponse.ok) throw new Error('Gagal memuat data bus stop');
        const busStopResult = await busStopResponse.json();
        processBusStopData(busStopResult);

        // 4. Render tampilan visual GIS
        updateLoading('Merender visualisasi GIS & menghitung rasio...');

        if (kecamatanGeoJSON && kecamatanGeoJSON.features && kecamatanGeoJSON.features.length > 0) {
            renderChoropleth();
        } else if (!kecamatanLayer) {
            useFallbackKecamatan();
        }

        renderBusStops();
        updateStatistics();
        buildDataTable();

        hideLoading();

    } catch (error) {
        console.error('Error loading data:', error);
        showError(error.message);
    }
}

// ==================== PENGOLAHAN DATA ====================

/**
 * Proses data batas kecamatan dari Overpass API
 * Konversi format Overpass ke GeoJSON
 */
function processKecamatanData(overpassData) {
    if (!overpassData || !overpassData.elements || overpassData.elements.length === 0) {
        console.warn('Tidak ada data kecamatan dari Overpass API, menggunakan fallback.');
        useFallbackKecamatan();
        return;
    }

    // Konversi Overpass relation ke GeoJSON menggunakan osmtogeojson
    try {
        const fullGeoJSON = osmtogeojson(overpassData);
        if (fullGeoJSON && fullGeoJSON.features && fullGeoJSON.features.length > 0) {
            // Filter hanya feature yang 100% cocok dengan 31 nama kecamatan resmi Kota Surabaya
            const matchedFeatures = [];
            const processedKec = new Set();

            fullGeoJSON.features.forEach(feature => {
                const name = extractKecamatanName(feature);
                const kec = findKecamatan(name);

                // Pastikan nama cocok dengan kecamatan Surabaya dan belum terduplikasi
                if (kec && !processedKec.has(kec.nama)) {
                    feature.properties.kecData = kec;
                    feature.properties.cleanName = kec.nama;
                    // Precompute bounding box untuk query spasial super cepat
                    feature._bbox = computeGeometryBbox(feature.geometry);
                    matchedFeatures.push(feature);
                    processedKec.add(kec.nama);
                }
            });

            console.log(`Berhasil memetakan ${matchedFeatures.length} dari ${pendudukData.length} kecamatan ke polygon spasial.`);

            if (matchedFeatures.length > 0) {
                kecamatanGeoJSON = {
                    type: 'FeatureCollection',
                    features: matchedFeatures
                };
            } else {
                useFallbackKecamatan();
            }
        } else {
            useFallbackKecamatan();
        }
    } catch (e) {
        console.warn('Error konversi osmtogeojson:', e);
        useFallbackKecamatan();
    }
}

/**
 * Fallback jika boundary kecamatan tidak tersedia:
 * Buat circle marker berdasarkan koordinat di data penduduk
 */
function useFallbackKecamatan() {
    console.log('Menggunakan fallback circle marker untuk kecamatan');
    kecamatanGeoJSON = null;

    // Buat layer group dengan circle marker
    kecamatanLayer = L.layerGroup();

    pendudukData.forEach(kec => {
        const radius = Math.sqrt(kec.penduduk) * 2;
        const color = getColor(kec.penduduk);

        const circle = L.circle([kec.lat, kec.lon], {
            radius: radius,
            fillColor: color,
            fillOpacity: 0.6,
            color: '#fff',
            weight: 2
        });

        circle.bindPopup(createPopupContent(kec.nama, kec));
        circle.on('click', function () {
            showInfoBox(kec.nama, kec);
        });

        kecamatanLayer.addLayer(circle);
    });

    kecamatanLayer.addTo(map);
}

/**
 * Proses data bus stop dari Overpass API
 */
function processBusStopData(overpassData) {
    if (!overpassData.elements) {
        console.warn('Tidak ada data bus stop dari Overpass API');
        busStopData = [];
        return;
    }

    busStopData = overpassData.elements
        .filter(el => el.type === 'node' && el.lat && el.lon)
        .map(el => ({
            id: el.id,
            lat: el.lat,
            lon: el.lon,
            name: (el.tags && el.tags.name) || 'Bus Stop',
            operator: (el.tags && el.tags.operator) || '-',
            network: (el.tags && el.tags.network) || '-',
            shelter: (el.tags && el.tags.shelter) || '-',
            route_ref: (el.tags && el.tags.route_ref) || '-'
        }));

    // Hitung bus stop per kecamatan
    assignBusStopsToKecamatan();
}

/**
 * Menghitung jumlah bus stop per kecamatan
 * Menggunakan analisis spasial sejati (Point-in-Polygon Ray Casting)
 * dengan fallback nearest-centroid jika halte di tepi luar batas jalan.
 */
function assignBusStopsToKecamatan() {
    // Reset counter dan daftar halte di setiap kecamatan
    pendudukData.forEach(kec => {
        kec.busStopCount = 0;
        kec.busStops = [];
    });

    const hasPolygons = kecamatanGeoJSON && kecamatanGeoJSON.features && kecamatanGeoJSON.features.length > 0;
    const features = hasPolygons ? kecamatanGeoJSON.features : [];

    busStopData.forEach(stop => {
        let assignedKec = null;

        // 1. Coba Point-in-Polygon (PIP) ke batas spasial poligon resmi
        if (hasPolygons) {
            for (let i = 0; i < features.length; i++) {
                const feat = features[i];
                const bbox = feat._bbox;

                // Bounding box filter (cepat, menghindari ray casting jika di luar kotak)
                if (bbox && (stop.lat < bbox.minLat || stop.lat > bbox.maxLat || stop.lon < bbox.minLon || stop.lon > bbox.maxLon)) {
                    continue;
                }

                // Ray casting sesungguhnya pada poligon ring
                if (isPointInGeoJSON(stop.lat, stop.lon, feat.geometry)) {
                    assignedKec = feat.properties.kecData;
                    break;
                }
            }
        }

        // 2. Jika tidak masuk poligon manapun (misal di bahu jalan perbatasan), alokasikan ke kecamatan terdekat
        if (!assignedKec) {
            assignedKec = findNearestKecamatan(stop.lat, stop.lon);
        }

        if (assignedKec) {
            assignedKec.busStopCount++;
            assignedKec.busStops.push(stop);
            stop.kecamatan = assignedKec.nama;
        } else {
            stop.kecamatan = 'Kota Surabaya';
        }
    });

    console.log('Alokasi spasial bus stop selesai. Krembangan terhitung:', 
        (pendudukData.find(k => k.nama === 'Krembangan') || {}).busStopCount, 'halte.');
}

// ==================== RENDERING ====================

/**
 * Render choropleth layer (pewarnaan kecamatan berdasarkan populasi)
 */
function renderChoropleth() {
    if (!kecamatanGeoJSON || !kecamatanGeoJSON.features) return;
    if (kecamatanLayer) {
        map.removeLayer(kecamatanLayer);
    }

    kecamatanLayer = L.geoJSON(kecamatanGeoJSON, {
        style: function (feature) {
            const kec = feature.properties.kecData || findKecamatan(extractKecamatanName(feature));
            const population = kec ? kec.penduduk : 0;

            return {
                fillColor: getColor(population),
                weight: 2,
                opacity: 1,
                color: '#ffffff',
                dashArray: '',
                fillOpacity: 0.65,
                className: 'kecamatan-polygon'
            };
        },
        onEachFeature: function (feature, layer) {
            const kec = feature.properties.kecData || findKecamatan(extractKecamatanName(feature));
            const name = kec ? kec.nama : extractKecamatanName(feature);

            if (kec) {
                layer.bindPopup(createPopupContent(name, kec), { className: 'custom-popup' });

                layer.on({
                    mouseover: function (e) {
                        const l = e.target;
                        l.setStyle({
                            weight: 3,
                            color: '#1a73e8',
                            fillOpacity: 0.85
                        });
                        l.bringToFront();
                    },
                    mouseout: function (e) {
                        kecamatanLayer.resetStyle(e.target);
                    },
                    click: function (e) {
                        showInfoBox(name, kec);
                        map.fitBounds(e.target.getBounds(), { padding: [40, 40] });
                        
                        // Highlight baris di tabel sidebar
                        document.querySelectorAll('.data-table tbody tr').forEach(tr => {
                            tr.classList.remove('active');
                            if (tr.querySelector('td') && tr.querySelector('td').textContent.trim() === name) {
                                tr.classList.add('active');
                                tr.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                            }
                        });
                    }
                });
            }
        }
    });

    kecamatanLayer.addTo(map);
}

/**
 * Render bus stop markers
 */
function renderBusStops() {
    if (busStopLayer) {
        map.removeLayer(busStopLayer);
    }

    busStopLayer = L.layerGroup();

    busStopData.forEach(stop => {
        const marker = L.circleMarker([stop.lat, stop.lon], {
            radius: 5,
            fillColor: '#1a73e8',
            color: '#ffffff',
            weight: 2,
            fillOpacity: 0.9
        });

        let popupHtml = `
            <div class="popup-title">🚌 ${stop.name}</div>
            <div class="popup-info">
                <strong>Kecamatan:</strong> Kec. ${stop.kecamatan || '-'}<br>
                <strong>Operator:</strong> ${stop.operator}<br>
                <strong>Rute:</strong> ${stop.route_ref}<br>
                <strong>Shelter:</strong> ${stop.shelter}<br>
                <strong>Koordinat:</strong> ${stop.lat.toFixed(5)}, ${stop.lon.toFixed(5)}
            </div>
        `;

        marker.bindPopup(popupHtml, { className: 'custom-popup' });
        busStopLayer.addLayer(marker);
    });

    busStopLayer.addTo(map);
}

// ==================== UI UPDATES ====================

/**
 * Update statistik di sidebar
 */
function updateStatistics() {
    const totalPenduduk = pendudukData.reduce((sum, k) => sum + k.penduduk, 0);
    const totalBusStop = busStopData.length;
    const avgRatio = totalPenduduk / (totalBusStop || 1);
    const kecCount = pendudukData.length;

    document.getElementById('totalPenduduk').textContent = formatNumber(totalPenduduk);
    document.getElementById('totalBusStop').textContent = formatNumber(totalBusStop);
    document.getElementById('totalKecamatan').textContent = kecCount;
    document.getElementById('avgRatio').textContent = formatNumber(Math.round(avgRatio));

    // Top 5 kecamatan terpadat
    const sorted = [...pendudukData].sort((a, b) => b.penduduk - a.penduduk);
    const top5Html = sorted.slice(0, 5).map((k, i) => `
        <div class="legend-item">
            <span style="font-weight:600; color:#1a73e8; min-width:20px;">${i + 1}.</span>
            <span style="flex:1;">${k.nama}</span>
            <span style="font-weight:600;">${formatNumber(k.penduduk)}</span>
        </div>
    `).join('');
    document.getElementById('topKecamatan').innerHTML = top5Html;
}

/**
 * Bangun tabel data perbandingan
 */
function buildDataTable() {
    const tbody = document.getElementById('dataTableBody');
    if (!tbody) return;

    // Sort by population descending
    const sorted = [...pendudukData].sort((a, b) => b.penduduk - a.penduduk);

    tbody.innerHTML = sorted.map(kec => {
        const ratio = kec.busStopCount > 0 ? Math.round(kec.penduduk / kec.busStopCount) : -1;
        const ratioText = ratio > 0 ? formatNumber(ratio) : 'N/A';
        const ratioClass = ratio <= 0 ? 'bad' : (ratio < 20000 ? 'good' : (ratio < 50000 ? 'medium' : 'bad'));
        const density = Math.round(kec.penduduk / kec.luas);

        return `
            <tr onclick="focusKecamatan('${kec.nama}')" title="Klik untuk zoom ke ${kec.nama}">
                <td>${kec.nama}</td>
                <td class="text-right">${formatNumber(kec.penduduk)}</td>
                <td class="text-right">${kec.busStopCount || 0}</td>
                <td class="text-right"><span class="ratio-badge ${ratioClass}">${ratioText}</span></td>
            </tr>
        `;
    }).join('');
}

/**
 * Tampilkan info box di peta
 */
function showInfoBox(name, kec) {
    const infoBox = document.getElementById('infoBox');
    if (!infoBox) return;

    const ratio = kec.busStopCount > 0 ? Math.round(kec.penduduk / kec.busStopCount) : 0;
    const density = Math.round(kec.penduduk / kec.luas);

    document.getElementById('infoTitle').textContent = `Kec. ${name}`;
    document.getElementById('infoPenduduk').textContent = formatNumber(kec.penduduk) + ' jiwa';
    document.getElementById('infoLuas').textContent = kec.luas + ' km²';
    document.getElementById('infoDensity').textContent = formatNumber(density) + ' jiwa/km²';
    document.getElementById('infoBusStop').textContent = (kec.busStopCount || 0) + ' unit';
    document.getElementById('infoRatio').textContent = ratio > 0
        ? '1 : ' + formatNumber(ratio) + ' penduduk'
        : 'Tidak ada bus stop';

    infoBox.classList.add('visible');
}

/**
 * Tutup info box
 */
function closeInfoBox() {
    document.getElementById('infoBox').classList.remove('visible');
}

/**
 * Fokus/zoom ke kecamatan tertentu
 */
function focusKecamatan(nama) {
    const kec = findKecamatan(nama);
    if (!kec) return;

    if (kecamatanGeoJSON && kecamatanLayer) {
        // Cari layer yang sesuai
        kecamatanLayer.eachLayer(function (layer) {
            if (layer.feature) {
                const layerName = extractKecamatanName(layer.feature);
                if (normalizeKecName(layerName) === normalizeKecName(nama)) {
                    map.fitBounds(layer.getBounds(), { padding: [30, 30] });
                    showInfoBox(nama, kec);
                    return;
                }
            }
        });
    } else if (kec.lat && kec.lon) {
        map.setView([kec.lat, kec.lon], 14);
        showInfoBox(nama, kec);
    }

    // Highlight row di tabel
    document.querySelectorAll('.data-table tbody tr').forEach(tr => {
        tr.classList.remove('active');
        if (tr.querySelector('td') && tr.querySelector('td').textContent === nama) {
            tr.classList.add('active');
        }
    });
}

// ==================== LAYER TOGGLES ====================

/**
 * Toggle visibility layer kecamatan
 */
function toggleKecamatanLayer(checkbox) {
    if (!kecamatanLayer) return;
    if (checkbox.checked) {
        map.addLayer(kecamatanLayer);
    } else {
        map.removeLayer(kecamatanLayer);
    }
}

/**
 * Toggle visibility layer bus stop
 */
function toggleBusStopLayer(checkbox) {
    if (!busStopLayer) return;
    if (checkbox.checked) {
        map.addLayer(busStopLayer);
    } else {
        map.removeLayer(busStopLayer);
    }
}

// ==================== HELPER FUNCTIONS ====================

/**
 * Mendapatkan warna berdasarkan populasi (choropleth)
 */
function getColor(population) {
    const breaks = CONFIG.breaks;
    const colors = CONFIG.colors;

    for (let i = 0; i < breaks.length; i++) {
        if (population <= breaks[i]) return colors[i];
    }
    return colors[colors.length - 1];
}

/**
 * Normalisasi nama kecamatan untuk matching
 */
function normalizeKecName(name) {
    if (!name) return '';
    return name
        .toLowerCase()
        .replace(/^kecamatan\s+/i, '')
        .replace(/\s+/g, '')
        .trim();
}

/**
 * Ekstrak nama kecamatan dari GeoJSON feature
 */
function extractKecamatanName(feature) {
    if (!feature || !feature.properties) return '';

    const p = feature.properties;
    const tags = p.tags || {};

    let name = tags.name
        || p.name
        || tags['name:id']
        || p['name:id']
        || tags.alt_name
        || p.alt_name
        || '';

    name = name.replace(/^Kecamatan\s+/i, '').trim();
    return name;
}

/**
 * Cari data kecamatan berdasarkan nama (strict & alias match)
 */
function findKecamatan(name) {
    if (!name) return null;
    const normalized = normalizeKecName(name);

    // 1. Prioritaskan exact match
    const exact = pendudukData.find(kec => normalizeKecName(kec.nama) === normalized);
    if (exact) return exact;

    // 2. Mapping alias khusus (variasi penulisan OSM)
    const aliases = {
        'karangpilang': 'Karangpilang',
        'karangpoh': null, // bukan kecamatan
        'pabeancantian': 'Pabean Cantian',
        'pabeancantikan': 'Pabean Cantian'
    };

    if (aliases[normalized] !== undefined) {
        const aliasTarget = aliases[normalized];
        return aliasTarget ? pendudukData.find(kec => kec.nama === aliasTarget) : null;
    }

    return null;
}

/**
 * Cari kecamatan terdekat dari koordinat
 */
function findNearestKecamatan(lat, lon) {
    let nearest = null;
    let minDist = Infinity;

    pendudukData.forEach(kec => {
        const dist = Math.sqrt(Math.pow(lat - kec.lat, 2) + Math.pow(lon - kec.lon, 2));
        if (dist < minDist) {
            minDist = dist;
            nearest = kec;
        }
    });

    return nearest;
}

/**
 * Menghitung Bounding Box dari sebuah geometri GeoJSON
 */
function computeGeometryBbox(geometry) {
    if (!geometry || !geometry.coordinates) return null;
    let minLat = Infinity, maxLat = -Infinity;
    let minLon = Infinity, maxLon = -Infinity;

    function processCoords(coords) {
        if (typeof coords[0] === 'number') {
            const lon = coords[0];
            const lat = coords[1];
            if (lat < minLat) minLat = lat;
            if (lat > maxLat) maxLat = lat;
            if (lon < minLon) minLon = lon;
            if (lon > maxLon) maxLon = lon;
        } else {
            for (let i = 0; i < coords.length; i++) {
                processCoords(coords[i]);
            }
        }
    }

    processCoords(geometry.coordinates);
    return { minLat, maxLat, minLon, maxLon };
}

/**
 * Algoritma Ray-Casting untuk menentukan apakah titik [lon, lat] berada di dalam polygon ring
 */
function pointInRing(pt, ring) {
    const x = pt[0], y = pt[1];
    let inside = false;
    for (let i = 0, j = ring.length - 1; i < ring.length; j = i++) {
        const xi = ring[i][0], yi = ring[i][1];
        const xj = ring[j][0], yj = ring[j][1];
        const intersect = ((yi > y) !== (yj > y)) && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
        if (intersect) inside = !inside;
    }
    return inside;
}

/**
 * Cek apakah titik koordinat berada di dalam geometri Polygon / MultiPolygon GeoJSON
 */
function isPointInGeoJSON(lat, lon, geometry) {
    if (!geometry || !geometry.coordinates) return false;
    const pt = [lon, lat];

    if (geometry.type === 'Polygon') {
        const rings = geometry.coordinates;
        if (!rings || rings.length === 0) return false;
        // Outer ring check
        if (!pointInRing(pt, rings[0])) return false;
        // Inner rings (holes) check
        for (let i = 1; i < rings.length; i++) {
            if (pointInRing(pt, rings[i])) return false;
        }
        return true;
    } else if (geometry.type === 'MultiPolygon') {
        const polys = geometry.coordinates;
        if (!polys) return false;
        for (let p = 0; p < polys.length; p++) {
            const rings = polys[p];
            if (rings && rings.length > 0 && pointInRing(pt, rings[0])) {
                let inHole = false;
                for (let h = 1; h < rings.length; h++) {
                    if (pointInRing(pt, rings[h])) {
                        inHole = true;
                        break;
                    }
                }
                if (!inHole) return true;
            }
        }
    }
    return false;
}

/**
 * Buat konten popup
 */
function createPopupContent(name, kec) {
    const density = Math.round(kec.penduduk / kec.luas);
    const ratio = kec.busStopCount > 0 ? Math.round(kec.penduduk / kec.busStopCount) : 0;

    return `
        <div class="popup-title">📍 Kecamatan ${name}</div>
        <div class="popup-info">
            <strong>Penduduk:</strong> ${formatNumber(kec.penduduk)} jiwa<br>
            <strong>Luas:</strong> ${kec.luas} km²<br>
            <strong>Kepadatan:</strong> ${formatNumber(density)} jiwa/km²<br>
            <strong>Bus Stop:</strong> ${kec.busStopCount || 0} unit<br>
            <strong>Rasio:</strong> ${ratio > 0 ? '1 bus stop : ' + formatNumber(ratio) + ' penduduk' : 'Tidak ada bus stop'}
        </div>
    `;
}

/**
 * Format angka dengan pemisah ribuan
 */
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

/**
 * Update status loading
 */
function updateLoading(message) {
    const loadingStep = document.getElementById('loadingStep');
    if (loadingStep) {
        loadingStep.textContent = message;
    }
}

/**
 * Sembunyikan loading overlay
 */
function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.classList.add('hidden');
    }
}

/**
 * Tampilkan error
 */
function showError(message) {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.innerHTML = `
            <div style="text-align:center; padding:20px;">
                <div style="font-size:2em; margin-bottom:10px;">⚠️</div>
                <div style="color:#c5221f; font-weight:600; margin-bottom:8px;">Error Memuat Data</div>
                <div style="color:#5f6368; font-size:0.85em; margin-bottom:16px;">${message}</div>
                <button onclick="location.reload()" style="
                    background:#1a73e8; color:white; border:none; padding:8px 20px;
                    border-radius:6px; cursor:pointer; font-size:0.9em;
                ">Coba Lagi</button>
            </div>
        `;
    }
}

