<?php
// ==========================================
// BAGIAN LOGIKA (BACKEND) - TETAP SAMA
// ==========================================
ini_set('memory_limit', '512M');
ini_set('max_execution_time', 300);

$pesan = "";
$file_terupload = "";
$file_hasil = "";

// Fungsi PHP Murni: Unsharp Masking
function process_xray_advanced($source_path, $output_path, $amount, $radius) {
    $info = getimagesize($source_path);
    $mime = $info['mime'];

    switch ($mime) {
        case 'image/jpeg': $img = imagecreatefromjpeg($source_path); break;
        case 'image/png':  $img = imagecreatefrompng($source_path); break;
        default: return false;
    }

    if (!$img) return false;

    imagefilter($img, IMG_FILTER_GRAYSCALE);
    imagefilter($img, IMG_FILTER_CONTRAST, -20);

    $w = imagesx($img);
    $h = imagesy($img);
    
    $imgBlur = imagecreatetruecolor($w, $h);
    imagecopy($imgBlur, $img, 0, 0, 0, 0, $w, $h);

    for ($i = 0; $i < $radius; $i++) {
        imagefilter($imgBlur, IMG_FILTER_GAUSSIAN_BLUR);
    }

    $factor = $amount / 20; 

    for ($y = 0; $y < $h; $y++) {
        for ($x = 0; $x < $w; $x++) {
            $rgbOrig = imagecolorat($img, $x, $y);
            $grayOrig = ($rgbOrig >> 16) & 0xFF;

            $rgbBlur = imagecolorat($imgBlur, $x, $y);
            $grayBlur = ($rgbBlur >> 16) & 0xFF;

            $diff = $grayOrig - $grayBlur;
            $newVal = $grayOrig + ($diff * $factor);
            $newVal = max(0, min(255, $newVal));

            $newColor = imagecolorallocate($img, $newVal, $newVal, $newVal);
            imagesetpixel($img, $x, $y, $newColor);
        }
    }

    imagedestroy($imgBlur);
    imagejpeg($img, $output_path, 95);
    imagedestroy($img);
    return true;
}

// --- PROSES UPLOAD ---
if (isset($_POST['submit'])) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

    $nama_file = basename($_FILES["imageFile"]["name"]);
    $target_file = $target_dir . "orig_" . time() . "_" . $nama_file;
    $output_file = $target_dir . "proc_" . time() . "_" . $nama_file;
    
    $amount = isset($_POST['amount']) ? (int)$_POST['amount'] : 50;
    $radius = isset($_POST['radius']) ? (int)$_POST['radius'] : 3;

    $tipe_file = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
    $ekstensi_ok = array("jpg", "jpeg", "png");

    if (!empty($_FILES["imageFile"]["name"]) && in_array($tipe_file, $ekstensi_ok)) {
        if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $target_file)) {
            $file_terupload = $target_file;
            $sukses = process_xray_advanced($target_file, $output_file, $amount, $radius);

            if ($sukses) {
                $pesan = "<div class='sukses'>✅ Foto diproses dengan Parameter Area!</div>";
                $file_hasil = $output_file;
            } else {
                $pesan = "<div class='gagal'>❌ Gagal memproses gambar.</div>";
            }
        }
    } else {
        $pesan = "<div class='gagal'>⚠️ File wajib JPG/PNG.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>X-Ray PHP Advanced + Particles</title>
    <style>
        /* CSS UNTUK PARTICLES */
        body { 
            margin: 0;
            padding: 0;
            font-family: sans-serif; 
            overflow-x: hidden;
            /* Warna Background Gelap agar particles terlihat */
            background-color: #1b1b2f; 
        }

        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1; /* Taruh di belakang */
        }

        /* CSS KONTEN ASLI (Dimodifikasi sedikit agar terlihat di atas background) */
        .container { 
            background: rgba(255, 255, 255, 0.95); /* Sedikit transparan */
            padding: 25px; 
            border-radius: 10px; 
            max-width: 850px; 
            margin: 50px auto; /* Margin atas ditambah */
            box-shadow: 0 10px 25px rgba(0,0,0,0.5); 
            position: relative;
            z-index: 1; /* Taruh di depan particles */
        }

        h2 { text-align: center; color: #333; }
        .row { display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px; }
        .col { flex: 1; min-width: 300px; }
        
        /* Style Slider */
        .slider-group { background: #f9f9f9; padding: 15px; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 15px; }
        input[type=range] { width: 100%; cursor: pointer; }
        label { font-weight: bold; display: block; margin-bottom: 5px; color: #444; }
        .val-display { float: right; color: #007bff; font-weight: bold; }
        .note { font-size: 12px; color: #777; display: block; margin-top: 5px; }

        button { background: #007bff; color: white; border: none; padding: 12px; width: 100%; border-radius: 5px; cursor: pointer; font-size: 16px; margin-top: 10px;}
        button:hover { background: #0056b3; }
        .sukses { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .gagal { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        img { max-width: 100%; border-radius: 5px; border: 1px solid #ccc; }

        /* Tombol Pojok Kanan Atas */
.btn-static {
    position: fixed; /* Agar tetap di pojok meskipun di-scroll */
    top: 20px;
    right: 20px;
    z-index: 1000; /* Pastikan di atas layer particles & container */
    
    background-color: #FF69B4; /* Warna Pink (sesuai garis partikel) */
    color: white;
    padding: 10px 25px;
    border-radius: 50px; /* Membuat tombol bulat lonjong */
    text-decoration: none; /* Hilangkan garis bawah link */
    font-weight: bold;
    font-family: sans-serif;
    box-shadow: 0 4px 15px rgba(255, 105, 180, 0.4); /* Efek glowing pink */
    transition: all 0.3s ease;
}

.btn-static:hover {
    background-color: #9B9D8E; /* Warna lebih terang saat hover */
    transform: translateY(-2px); /* Efek naik sedikit */
    box-shadow: 0 6px 20px rgba(255, 105, 180, 0.6);
}
    </style>
</head>
<body>
<a href="index.php" class="btn-static">Static</a>
<div id="particles-js"></div>

<div class="container">
    <h2>X-Ray Processor (PHP Native)</h2>
    <?php echo $pesan; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col">
                <label>Upload X-Ray:</label>
                <input type="file" name="imageFile" required accept="image/*" style="margin-bottom: 20px;">
                
                <div class="slider-group">
                    <label>
                        Kekuatan (Contrast/Clip)
                        <span class="val-display" id="disp_amt">50</span>
                    </label>
                    <input type="range" name="amount" min="10" max="100" value="50" oninput="document.getElementById('disp_amt').innerText = this.value">
                    <span class="note">Semakin tinggi, tulang semakin putih & tegas.</span>
                </div>

                <div class="slider-group">
                    <label>
                        Area Jangkauan (Radius)
                        <span class="val-display" id="disp_rad">3</span>
                    </label>
                    <input type="range" name="radius" min="1" max="10" value="3" oninput="document.getElementById('disp_rad').innerText = this.value">
                    <span class="note">
                        <b>Kecil (1-3):</b> Menajamkan detail halus/noise.<br>
                        <b>Besar (4-10):</b> Menajamkan struktur tulang besar.
                    </span>
                </div>

                <button type="submit" name="submit">Proses Gambar</button>
            </div>

            <div class="col">
                <?php if ($file_hasil != ""): ?>
                    <label>Hasil Proses:</label>
                    <img src="<?php echo $file_hasil; ?>" alt="Hasil">
                    <br><br>
                    <label>Original:</label>
                    <img src="<?php echo $file_terupload; ?>" alt="Asli" style="opacity: 0.7; width: 50%;">
                <?php else: ?>
                    <div style="text-align:center; padding: 50px; color: #ccc; border: 2px dashed #ddd; border-radius: 8px;">
                        Preview hasil akan muncul di sini.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
<script>
    particlesJS('particles-js', {
      "particles": {
        "number": {
          "value": 80,
          "density": {
            "enable": true,
            "value_area": 800
          }
        },
        "color": {
          "value": "#97F5C8"
        },
        "shape": {
          "type": "circle"
        },
        "opacity": {
          "value": 0.5,
          "random": false
        },
        "size": {
          "value": 2,
          "random": true
        },
        "line_linked": {
          "enable": true,
          "distance": 150,
          "color": "#FF69B4",
          "opacity": 0.8,
          "width": 1
        },
        "move": {
          "enable": true,
          "speed": 6,
          "direction": "none",
          "random": false,
          "straight": false,
          "out_mode": "out",
          "bounce": false
        }
      },
      "interactivity": {
        "detect_on": "canvas",
        "events": {
          "onhover": {
            "enable": true,
            "mode": "repulse"
          },
          "onclick": {
            "enable": true,
            "mode": "push"
          },
          "resize": true
        },
        "modes": {
          "grab": {
            "distance": 400,
            "line_linked": {
              "opacity": 1
            }
          },
          "bubble": {
            "distance": 400,
            "size": 40,
            "duration": 2,
            "opacity": 8,
            "speed": 3
          },
          "repulse": {
            "distance": 200,
            "duration": 0.4
          },
          "push": {
            "particles_nb": 4
          },
          "remove": {
            "particles_nb": 2
          }
        }
      },
      "retina_detect": true
    });
</script>

</body>
</html>