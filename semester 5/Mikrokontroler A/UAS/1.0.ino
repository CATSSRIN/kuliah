/*
  GABUNGAN: MQTT Slider Control + PID Motor Speed
  Merged by: Gemini
  Sources: fix selesai no debat.ino & hpwork.ino
*/

#include <WiFi.h>
#include <PubSubClient.h>

// ==========================================
// 1. KONFIGURASI WIFI & MQTT
// ==========================================
const char* ssid = "HOTSPOT@UPNJATIM.AC.ID"; // Sesuaikan jika perlu
const char* password = "belanegara";
const char* mqtt_server = "broker.hivemq.com";
const char* mqtt_topic = "esp32/motor/control"; // Topic untuk slider

WiFiClient espClient;
PubSubClient client(espClient);

// ==========================================
// 2. KONFIGURASI MOTOR & PIN
// ==========================================
int motor1Pin1 = 27;
int motor1Pin2 = 26;
int enable1Pin = 12;
const byte pin_rpm = 13;

// PWM Properties
const int freq = 30000;
const int pwmChannel = 0;
const int resolution = 8;

// ==========================================
// 3. VARIABEL PID & RPM
// ==========================================
volatile unsigned long rev = 0;
unsigned long last_rev_count = 0;
unsigned long last_rpm_time = 0;

float rpm = 0;
float rpm_filtered = 0;

// PID Variables
float P, I, D;
float Kc = 0.003;       // Proportional Gain (Sesuaikan tuning di sini)
float tauI = 1;         // Integral Time
float tauD = 1;         // Derivative Time

float sp = 0;           // SETPOINT (Target RPM) - Diubah via MQTT
float pv = 0;           // Process Value (RPM saat ini)
float pv_last = 0;
float ierr = 0;
float dt = 0;
int op = 0;             // Output PWM

unsigned long ts = 0, new_ts = 0;

// Interrupt Service Routine untuk Encoder
void IRAM_ATTR isr() {
  rev++;
}

// ==========================================
// 4. FUNGSI WIFI & MQTT
// ==========================================
void setup_wifi() {
  delay(10);
  Serial.print("Connecting to ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi connected. IP: ");
  Serial.println(WiFi.localIP());
}

// Callback: Saat data diterima dari HP (Slider)
void callback(char* topic, byte* message, unsigned int length) {
  String msg = "";
  for (int i = 0; i < length; i++) {
    msg += (char)message[i];
  }
  msg.trim();
  
  Serial.print("MQTT Received: ");
  Serial.println(msg);

  // Logika Parsing Pesan
  if (msg == "stop" || msg == "off") {
    sp = 0; // Set target RPM ke 0
    Serial.println("Target RPM set to 0 (STOP)");
  } 
  else {
    // Asumsi pesan adalah angka dari Slider (Target RPM)
    float new_sp = msg.toFloat();
    // Batasi target RPM agar tidak terlalu gila (misal max 10000)
    if (new_sp >= 0 && new_sp <= 10000) {
      sp = new_sp;
      Serial.print("New Setpoint (Target RPM): ");
      Serial.println(sp);
    }
  }
}

void reconnect() {
  while (!client.connected()) {
    Serial.print("Attempting MQTT connection...");
    String clientId = "ESP32_PID_Client_";
    clientId += String(random(0xffff), HEX);
    
    if (client.connect(clientId.c_str())) {
      Serial.println("connected");
      client.subscribe(mqtt_topic); // Subscribe ke topic slider
    } else {
      Serial.print("failed, rc=");
      Serial.print(client.state());
      delay(5000);
    }
  }
}

// ==========================================
// 5. LOGIKA RPM & PID
// ==========================================
void calculateRPM() {
  unsigned long current_time = millis();
  unsigned long time_elapsed = current_time - last_rpm_time;

  // Hitung RPM setiap 1000ms (1 detik) agar akurat
  if (time_elapsed >= 1000) { 
    noInterrupts();
    unsigned long current_rev = rev;
    interrupts();
    
    int holes = 2; // Jumlah lubang encoder disk
    float rotations = (float)(current_rev - last_rev_count) / holes;
    rpm = (rotations * 60000.0) / time_elapsed;
    
    // Low-pass filter sederhana
    rpm_filtered = 0.7 * rpm_filtered + 0.3 * rpm;
    
    last_rev_count = current_rev;
    last_rpm_time = current_time;
    
    // Debugging info
    Serial.print("RPM: "); Serial.print(rpm);
    Serial.print(" | Filtered: "); Serial.print(rpm_filtered);
    Serial.print(" | Target (SP): "); Serial.println(sp);
  }
}

float run_pid(float sp, float pv, float dt) {
  // PID Coefficients derived from Kc, tauI, tauD
  float KP = Kc;
  float KI = Kc / tauI;
  float KD = Kc * tauD; 
  
  float ophi = 255; // Max PWM
  float oplo = 0;   // Min PWM
  
  float error = sp - pv;
  
  ierr = ierr + KI * error * dt;
  float dpv = (pv - pv_last) / dt;
  
  float P_term = KP * error;
  float I_term = ierr;
  float D_term = -KD * dpv;
  
  float output = P_term + I_term + D_term;
  
  // Anti-reset windup & Clamping
  if ((output < oplo) || (output > ophi)) {
    ierr = ierr - KI * error * dt; // Batalkan integral jika saturasi
    output = constrain(output, oplo, ophi);
  }
  
  return output;
}

// ==========================================
// 6. SETUP & LOOP
// ==========================================
void setup() {
  Serial.begin(115200);
  
  // Setup Pins
  pinMode(motor1Pin1, OUTPUT);
  pinMode(motor1Pin2, OUTPUT);
  pinMode(enable1Pin, OUTPUT);
  pinMode(pin_rpm, INPUT_PULLUP);
  
  attachInterrupt(digitalPinToInterrupt(pin_rpm), isr, RISING);

  // PWM Setup
  ledcSetup(pwmChannel, freq, resolution);
  ledcAttachPin(enable1Pin, pwmChannel);
  
  // Motor Direction (Default Forward)
  digitalWrite(motor1Pin1, HIGH);
  digitalWrite(motor1Pin2, LOW);

  // Init Wifi & MQTT
  setup_wifi();
  client.setServer(mqtt_server, 1883);
  client.setCallback(callback);
  
  ts = millis();
  last_rpm_time = millis();
}

void loop() {
  // 1. Jaga koneksi MQTT
  if (!client.connected()) {
    reconnect();
  }
  client.loop();

  new_ts = millis();
  
  // 2. Hitung RPM terus menerus (di dalam fungsi ada timer 1 detik)
  calculateRPM();
  
  // 3. Jalankan Loop PID setiap 1 detik (sinkron dengan pembacaan RPM)
  // Catatan: Jika ingin respons lebih cepat, interval RPM harus diperkecil, 
  // tapi akurasi RPM rendah akan turun.
  if (new_ts - ts >= 1000) { 
    pv = rpm; // Menggunakan RPM aktual sebagai Process Value
    dt = (new_ts - ts) / 1000.0;
    ts = new_ts;
    
    // Hitung output PWM berdasarkan Setpoint (dari MQTT) vs RPM Asli
    op = run_pid(sp, pv, dt);
    
    // Terapkan ke motor
    ledcWrite(pwmChannel, op);
    pv_last = pv;
    
    Serial.print("PID Output (PWM): ");
    Serial.println(op);
  }
  
  delay(10); // Delay kecil untuk stabilitas
}