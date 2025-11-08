<?php
// detail.php

require_once 'db_config.php'; // Sertakan file konfigurasi database

// Ambil simbol dari URL
$symbol = $_GET['symbol'] ?? null;
$ticker = null;

if ($symbol) {
    try {
        // Siapkan query untuk mengambil satu koin
        $stmt = $pdo->prepare("SELECT * FROM tickers WHERE symbol = :symbol");
        $stmt->execute(['symbol' => $symbol]);
        $ticker = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = "Error mengambil data: " . $e->getMessage();
    }
} else {
    $error = "Tidak ada simbol koin yang dipilih.";
}

// Fungsi helper yang sama dari index.php
function formatPrice($price) {
    if ($price === null) return 'N/A';
    if ($price < 1) {
        return 'Rp ' . number_format($price, 8, ',', '.');
    } elseif ($price < 100) {
        return 'Rp ' . number_format($price, 4, ',', '.');
    } else {
        return 'Rp ' . number_format($price, 0, ',', '.');
    }
}

function formatPercentage($percent) {
    if ($percent === null) return 'N/A';
    $class = $percent >= 0 ? 'positive' : 'negative';
    $sign = $percent >= 0 ? '+' : '';
    return "<span class=\"{$class}\">" . $sign . number_format($percent, 2, ',', '.') . "%</span>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail: <?= $ticker ? htmlspecialchars($ticker['symbol']) : 'Error' ?> | Ringkasan Pasar</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --background-color-dark: #121212;
            --card-background-dark: #1e1e1e;
            --text-color-dark: #e0e0e0;
            --border-color-dark: #333;
            --input-bg-dark: #2a2a2a;
            --positive-color: #4CAF50;
            --negative-color: #f44336;
            --subtle-text-dark: #888;
            --accent-color: #ffc107;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--background-color-dark);
            color: var(--text-color-dark);
            transition: background-color 0.3s, color 0.3s;
            overflow-x: hidden;
        }

        body.light-mode {
            --background-color-dark: #f0f2f5;
            --card-background-dark: #ffffff;
            --text-color-dark: #333;
            --border-color-dark: #e0e0e0;
            --input-bg-dark: #ffffff;
            --subtle-text-dark: #666;
        }

        #canvas-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.1;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: var(--card-background-dark);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
        }

        /* Tombol Kembali */
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            font-size: 0.9em;
            color: var(--accent-color);
            text-decoration: none;
            padding: 8px 15px;
            background-color: var(--input-bg-dark);
            border-radius: 20px;
            transition: background-color 0.3s;
        }
        .back-link:hover {
            background-color: var(--border-color-dark);
        }

        .mode-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 8px 12px;
            background-color: var(--input-bg-dark);
            border-radius: 20px;
            font-size: 0.9em;
            color: var(--text-color-dark);
            border: 1px solid var(--border-color-dark);
            transition: background-color 0.3s, border-color 0.3s;
            float: right; /* Taruh di kanan */
        }
        .mode-toggle:hover {
            background-color: var(--border-color-dark);
        }
        .mode-toggle i {
            color: var(--accent-color);
        }

        .detail-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color-dark);
        }
        .detail-header .logo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--subtle-text-dark);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.5em;
            font-weight: bold;
            color: white;
            flex-shrink: 0;
        }
        .detail-header h1 {
            font-size: 2em;
            margin: 0;
            color: var(--text-color-dark);
        }
        .detail-header .pair {
            font-size: 1em;
            color: var(--subtle-text-dark);
        }

        /* Grid untuk menampilkan data */
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .detail-card {
            background-color: var(--background-color-dark);
            border: 1px solid var(--border-color-dark);
            padding: 20px;
            border-radius: 10px;
        }
        .detail-card .label {
            font-size: 0.9em;
            color: var(--subtle-text-dark);
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .detail-card .value {
            font-size: 1.5em;
            font-weight: 600;
        }
        .percentage.positive {
            color: var(--positive-color);
        }
        .percentage.negative {
            color: var(--negative-color);
        }

        /* Pesan error */
        .error-message {
            color: var(--negative-color);
            background-color: rgba(244, 67, 54, 0.1);
            border: 1px solid var(--negative-color);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        @import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");
    </style>
</head>
<body>
    <canvas id="canvas-background"></canvas>

    <div class="container">
        <div class="mode-toggle" id="modeToggle">
            <i class="fas fa-sun"></i> Light Mode
        </div>

        <a href="index.php" class="back-link">&larr; Kembali ke Ringkasan Pasar</a>

        <?php if ($ticker): ?>
            <div class="detail-header">
                <div class="logo"><?= strtoupper(substr($ticker['symbol'], 0, 1)) ?></div>
                <div>
                    <h1><?= htmlspecialchars($ticker['symbol']) ?></h1>
                    <div class="pair"><?= htmlspecialchars(str_replace('_', '/', $ticker['symbol'])) ?></div>
                </div>
            </div>

            <div class="detail-grid">
                <div class="detail-card">
                    <div class="label">Harga Terakhir</div>
                    <div class="value"><?= formatPrice($ticker['last_price']) ?></div>
                </div>
                <div class="detail-card">
                    <div class="label">Perubahan 24 Jam</div>
                    <div class="value percentage"><?= formatPercentage($ticker['price_change_percent']) ?></div>
                </div>
                <div class="detail-card">
                    <div class="label">Tertinggi 24 Jam</div>
                    <div class="value"><?= formatPrice($ticker['high_price']) ?></div>
                </div>
                <div class="detail-card">
                    <div class="label">Terendah 24 Jam</div>
                    <div class="value"><?= formatPrice($ticker['low_price']) ?></div>
                </div>
                <div class="detail-card">
                    <div class="label">Volume 24 Jam</div>
                    <div class="value"><?= number_format($ticker['volume'], 2, ',', '.') ?></div>
                </div>
                <div class="detail-card">
                    <div class="label">Diperbarui Pada</div>
                    <div class="value" style="font-size: 1.2em;"><?= (new DateTime($ticker['fetched_at']))->format('d M Y, H:i:s') ?></div>
                </div>
            </div>

            <div style="margin-top: 30px; padding: 20px; background: var(--background-color-dark); border: 1px solid var(--border-color-dark); border-radius: 10px; text-align: center; color: var(--subtle-text-dark);">
                <h3>Grafik Historis</h3>
                <p>Fitur ini memerlukan perubahan besar pada cara data disimpan (menyimpan data setiap 5 menit, bukan hanya menimpa data terakhir).</p>
                <p>Saat ini, API dan database kita hanya menyimpan ringkasan 24 jam.</p>
            </div>

        <?php elseif (isset($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php else: ?>
            <div class="error-message">Koin tidak ditemukan dalam database.</div>
        <?php endif; ?>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modeToggle = document.getElementById('modeToggle');
            const body = document.body;

            // --- Light/Dark Mode Toggle ---
            const currentMode = localStorage.getItem('theme') || 'dark';
            if (currentMode === 'light') {
                body.classList.add('light-mode');
                modeToggle.innerHTML = '<i class="fas fa-moon"></i> Dark Mode';
            } else {
                modeToggle.innerHTML = '<i class="fas fa-sun"></i> Light Mode';
            }

            modeToggle.addEventListener('click', function() {
                body.classList.toggle('light-mode');
                if (body.classList.contains('light-mode')) {
                    localStorage.setItem('theme', 'light');
                    modeToggle.innerHTML = '<i class="fas fa-moon"></i> Dark Mode';
                    drawBackground(true);
                } else {
                    localStorage.setItem('theme', 'dark');
                    modeToggle.innerHTML = '<i class="fas fa-sun"></i> Light Mode';
                    drawBackground(false);
                }
            });

            // --- Canvas Background Animation ---
            const canvasBg = document.getElementById('canvas-background');
            const ctxBg = canvasBg.getContext('2d');
            let animationFrameId;
            let particles = [];
            const particleCount = 50;
            const maxDistance = 100;

            function resizeCanvas() {
                canvasBg.width = window.innerWidth;
                canvasBg.height = window.innerHeight;
                particles = [];
                for (let i = 0; i < particleCount; i++) {
                    particles.push(new Particle());
                }
            }

            window.addEventListener('resize', resizeCanvas);
            resizeCanvas();

            function Particle() {
                this.x = Math.random() * canvasBg.width;
                this.y = Math.random() * canvasBg.height;
                this.vx = (Math.random() - 0.5) * 1;
                this.vy = (Math.random() - 0.5) * 1;
                this.radius = Math.random() * 2 + 1;
            }

            function drawBackground(isLightMode) {
                if (animationFrameId) {
                    cancelAnimationFrame(animationFrameId);
                }
                ctxBg.clearRect(0, 0, canvasBg.width, canvasBg.height);

                const lineColor = isLightMode ? 'rgba(0, 0, 0, 0.05)' : 'rgba(255, 255, 255, 0.05)';
                const particleColor = isLightMode ? 'rgba(0, 0, 0, 0.3)' : 'rgba(255, 255, 255, 0.3)';

                function animate() {
                    ctxBg.clearRect(0, 0, canvasBg.width, canvasBg.height);

                    for (let i = 0; i < particleCount; i++) {
                        let p1 = particles[i];
                        p1.x += p1.vx;
                        p1.y += p1.vy;

                        if (p1.x < 0 || p1.x > canvasBg.width) p1.vx *= -1;
                        if (p1.y < 0 || p1.y > canvasBg.height) p1.vy *= -1;

                        ctxBg.beginPath();
                        ctxBg.arc(p1.x, p1.y, p1.radius, 0, Math.PI * 2);
                        ctxBg.fillStyle = particleColor;
                        ctxBg.fill();

                        for (let j = i + 1; j < particleCount; j++) {
                            let p2 = particles[j];
                            let distance = Math.sqrt(Math.pow(p1.x - p2.x, 2) + Math.pow(p1.y - p2.y, 2));

                            if (distance < maxDistance) {
                                ctxBg.beginPath();
                                ctxBg.moveTo(p1.x, p1.y);
                                ctxBg.lineTo(p2.x, p2.y);
                                ctxBg.strokeStyle = lineColor;
                                ctxBg.lineWidth = 0.5;
                                ctxBg.stroke();
                            }
                        }
                    }
                    animationFrameId = requestAnimationFrame(animate);
                }
                animate();
            }

            drawBackground(body.classList.contains('light-mode'));
        });
    </script>
</body>
</html>