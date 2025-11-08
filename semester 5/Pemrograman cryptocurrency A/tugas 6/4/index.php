<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vindax Data Fetcher</title>
    <style>
        body { font-family: Arial, sans-serif; display: grid; place-items: center; min-height: 200px; background-color: #f4f4f4; }
        .container { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        #fetchButton {
            background-color: #007bff; color: white; border: none;
            padding: 10px 20px; font-size: 16px; border-radius: 5px;
            cursor: pointer; transition: background-color 0.3s;
        }
        #fetchButton:disabled { background-color: #aaa; cursor: not-allowed; }
        #fetchButton:hover:not(:disabled) { background-color: #0056b3; }
        #status {
            margin-top: 20px; font-size: 14px; color: #333;
            background-color: #f0f0f0; padding: 10px; border-radius: 5px;
            text-align: left; white-space: pre-wrap; /* Agar \n terbaca */
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Update Data Ticker Vindax</h2>
        <p>Klik tombol di bawah untuk mengambil data terbaru dari API Vindax.</p>
        <button id="fetchButton">Ambil Data Sekarang</button>
        <div id="status">Status: Menunggu...</div>
    </div>

    <script>
        document.getElementById('fetchButton').addEventListener('click', function() {
            var button = this;
            var statusDiv = document.getElementById('status');

            // Nonaktifkan tombol selama proses
            button.disabled = true;
            statusDiv.textContent = "Sedang mengambil data, mohon tunggu...";

            // Panggil fetch_data.php menggunakan AJAX (Fetch API)
            fetch('fetch_data.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.statusText);
                    }
                    return response.text(); // Ambil respons sebagai teks
                })
                .then(text => {
                    // Tampilkan pesan sukses/error dari fetch_data.php
                    statusDiv.textContent = text;
                    button.disabled = false; // Aktifkan kembali tombol
                })
                .catch(error => {
                    // Tampilkan pesan error jika AJAX gagal
                    statusDiv.textContent = 'Error saat menjalankan script: ' + error.message;
                    button.disabled = false; // Aktifkan kembali tombol
                });
        });
    </script>

</body>
</html>