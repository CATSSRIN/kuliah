<?php include 'process.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLAHE Visualizer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .preview-box {
            border: 2px dashed #ddd;
            padding: 10px;
            background: #f9f9f9;
            min-height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .preview-img {
            max-width: 100%;
            height: auto;
            max-height: 400px;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">
    <?php if (!extension_loaded('imagick')): ?>
        <div class="alert alert-danger text-center">
            <strong>PERINGATAN KERAS:</strong> Ekstensi PHP <code>Imagick</code> belum aktif.<br>
            Sistem tidak akan bekerja. Harap aktifkan di <code>php.ini</code> atau install module tersebut.
        </div>
    <?php endif; ?>

    <h2 class="text-center mb-4">Visualisasi CLAHE (Image Enhancement)</h2>

    <?php if ($errorMsg): ?>
        <div class="alert alert-danger"><?php echo $errorMsg; ?></div>
    <?php endif; ?>
    
    <?php if ($successMsg): ?>
        <div class="alert alert-success"><?php echo $successMsg; ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <form action="index.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label fw-bold">1. Upload Gambar</label>
                            <input type="file" class="form-control" name="imageFile" required>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label class="form-label">Clip Limit (Kontras): <b id="clipDisplay"><?php echo $defaultClip; ?></b></label>
                            <input type="range" class="form-range" name="clipLimit" min="0.5" max="10" step="0.5" value="<?php echo $defaultClip; ?>" oninput="document.getElementById('clipDisplay').innerText = this.value">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tile Size (Grid): <b id="tileDisplay"><?php echo $defaultTile; ?></b></label>
                            <input type="range" class="form-range" name="tileSize" min="20" max="200" step="10" value="<?php echo $defaultTile; ?>" oninput="document.getElementById('tileDisplay').innerText = this.value">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Proses Sekarang</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header text-center">Original</div>
                        <div class="card-body preview-box">
                            <?php if ($paths && isset($paths['original'])): ?>
                                <img src="<?php echo $paths['original']; ?>" class="preview-img">
                            <?php else: ?>
                                <span class="text-muted">Upload gambar untuk melihat</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header text-center bg-success text-white">Hasil CLAHE</div>
                        <div class="card-body preview-box">
                            <?php if ($paths && isset($paths['result'])): ?>
                                <img src="<?php echo $paths['result']; ?>" class="preview-img">
                            <?php else: ?>
                                <span class="text-muted">Hasil muncul disini</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>