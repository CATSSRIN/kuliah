<?php

class ClaheService {
    
    private $uploadDir = 'uploads/';

    public function __construct() {
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function processImage($fileData, $tileWidth, $tileHeight, $clipLimit) {
        // Cek Ekstensi DULU
        if (!extension_loaded('imagick')) {
            throw new Exception("Error Fatal: Ekstensi 'imagick' tidak aktif di PHP Anda. Harap aktifkan di php.ini.");
        }

        // Validasi Upload
        if ($fileData['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Error Upload: Kode Error " . $fileData['error']);
        }

        $filename = uniqid() . '_' . basename($fileData['name']);
        $originalPath = $this->uploadDir . 'original_' . $filename;
        
        // Simpan File Asli
        if (!move_uploaded_file($fileData['tmp_name'], $originalPath)) {
            throw new Exception("Gagal menyimpan gambar ke folder uploads/.");
        }

        try {
            $imagick = new \Imagick($originalPath);

            // Terapkan CLAHE
            $imagick->claheImage($tileWidth, $tileHeight, 128, $clipLimit);

            // Simpan Hasil
            $resultPath = $this->uploadDir . 'clahe_' . $filename;
            $imagick->writeImage($resultPath);
            
            $imagick->clear();
            $imagick->destroy();

            // KEMBALIKAN DUA PATH (Asli & Edit)
            return [
                'original' => $originalPath,
                'result'   => $resultPath
            ];

        } catch (Exception $e) {
            // Hapus file sisa jika error
            if (file_exists($originalPath)) unlink($originalPath);
            throw $e; // Lempar error ke process.php
        }
    }
}
?>