<?php

// 1. GANTI DENGAN TOKEN BOT ANDA
$token = "8327944500:AAHwwTH1x3mxcrSItv4dQVLcEQ7oVkEWrg4"; //no longer in use
$apiUrl = "https://api.telegram.org/bot" . $token;

// 2. AMBIL UPDATE DARI TELEGRAM
// 'php://input' digunakan untuk membaca data mentah dari body request
$update = json_decode(file_get_contents("php://input"), TRUE);

// 3. PROSES PESAN
if (isset($update["message"])) {
    // Ambil ID chat dan teks pesan
    $chatId = $update["message"]["chat"]["id"];
    $messageText = $update["message"]["text"];

    // Siapkan balasan
    $responseText = "";

    // Logika sederhana untuk merespon perintah
    if ($messageText == "/start") {
        $responseText = "Halo! Selamat datang di bot saya. Kirimkan saya pesan apa saja, dan saya akan mengulanginya.";
    } else {
        // Balas dengan pesan yang sama (echo bot)
        $responseText = "Anda mengirim: " . $messageText;
    }

    // Kirim balasan
    sendMessage($chatId, $responseText);
}

/**
 * Fungsi untuk mengirim pesan ke Telegram API
 * @param int $chatId ID chat tujuan
 * @param string $text Teks pesan yang akan dikirim
 */
function sendMessage($chatId, $text) {
    global $apiUrl;
    // urlencode() penting untuk memastikan teks dikirim dengan benar
    $url = $apiUrl . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($text);
    
    // Kirim request ke Telegram
    file_get_contents($url);
}

?>