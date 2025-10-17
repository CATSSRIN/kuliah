#include <WiFi.h>
#include <PubSubClient.h>
#include <Arduino.h>

// --- KONFIGURASI PIN & PWM ---
// Pin Motor
int motor1Pin1 = 27;
int motor1Pin2 = 26;
int enable1Pin = 12; // Pin ini yang akan menerima sinyal PWM

// Properti PWM
const int freq = 30000;       // Frekuensi PWM
const int pwmChannel = 0;     // Channel PWM yang digunakan (0-15)
const int resolution = 8;     // Resolusi 8-bit (nilai 0-255)

// --- KONFIGURASI JARINGAN & MQTT ---
const char* ssid = "HOTSPOT@UPNJATIM.AC.ID"; // Ganti dengan nama WiFi Anda
const char* password = "belanegara";         // Ganti dengan password WiFi Anda

const char* mqttServer = "broker.emqx.io"; // Broker MQTT publik
const int mqttPort = 1883;
const char* mqttTopic = "kontrolbas/pwm"; // Topik untuk menerima nilai PWM

// --- INISIALISASI ---
WiFiClient espClient;
PubSubClient client(espClient);

void setup() {
  Serial.begin(115200);

  // Atur pin motor sebagai OUTPUT
  pinMode(motor1Pin1, OUTPUT);
  pinMode(motor1Pin2, OUTPUT);
  pinMode(enable1Pin, OUTPUT);

  // Konfigurasi fungsi PWM
  ledcSetup(pwmChannel, freq, resolution);

  // Hubungkan channel PWM ke pin GPIO (enable1Pin)
  ledcAttachPin(enable1Pin, pwmChannel);

  // Atur arah putaran motor default (misalnya, maju)
  // Anda bisa mengubah ini ke LOW dan HIGH untuk arah sebaliknya
  digitalWrite(motor1Pin1, HIGH);
  digitalWrite(motor1Pin2, LOW);

  // Hubungkan ke WiFi
  connectToWiFi();

  // Hubungkan ke Broker MQTT
  client.setServer(mqttServer, mqttPort);
  client.setCallback(receivedCallback); // Atur fungsi callback
  connectToMQTT();
}

// --- FUNGSI UTAMA ---
void loop() {
  // Jika koneksi MQTT terputus, coba sambungkan kembali
  if (!client.connected()) {
    connectToMQTT();
  }
  // Menjaga koneksi dan memproses pesan masuk
  client.loop();
}

// --- FUNGSI PENDUKUNG ---

/**
 * @brief Fungsi ini dipanggil setiap kali ada pesan masuk dari topik MQTT yang di-subscribe.
 * @param topic Topik dari pesan yang masuk.
 * @param payload Isi pesan (data).
 * @param length Panjang pesan.
 */
void receivedCallback(char* topic, byte* payload, unsigned int length) {
  Serial.print("Pesan diterima dari topik: ");
  Serial.println(topic);

  // Buat variabel untuk menampung pesan
  String message = "";
  for (int i = 0; i < length; i++) {
    message += (char)payload[i];
  }
  Serial.print("Isi Pesan: ");
  Serial.println(message);

  // Ubah pesan (String) menjadi angka (integer)
  int pwmValue = message.toInt();

  // Validasi nilai PWM agar berada dalam rentang 0-255
  if (pwmValue < 0) {
    pwmValue = 0;
  } else if (pwmValue > 255) {
    pwmValue = 255;
  }

  Serial.print("Mengatur PWM ke: ");
  Serial.println(pwmValue);

  // Tulis nilai PWM ke motor
  ledcWrite(pwmChannel, pwmValue);
}

/**
 * @brief Menghubungkan ESP32 ke jaringan WiFi.
 */
void connectToWiFi() {
  Serial.print("Menghubungkan ke WiFi: ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi terhubung!");
  Serial.print("Alamat IP: ");
  Serial.println(WiFi.localIP());
}

/**
 * @brief Menghubungkan ESP32 ke broker MQTT.
 */
void connectToMQTT() {
  while (!client.connected()) {
    Serial.print("Mencoba koneksi ke MQTT broker...");
    // Coba hubungkan dengan sebuah client ID unik
    String clientId = "ESP32Client-";
    clientId += String(random(0xffff), HEX);

    if (client.connect(clientId.c_str())) {
      Serial.println("Terhubung!");
      // Subscribe ke topik untuk menerima data
      client.subscribe(mqttTopic);
      Serial.print("Subscribe ke topik: ");
      Serial.println(mqttTopic);
    } else {
      Serial.print("Gagal, rc=");
      Serial.print(client.state());
      Serial.println(" Coba lagi dalam 5 detik");
      delay(5000);
    }
  }
}