/*
  GABUNGAN FINAL: MQTT Slider + PID + RPM Reading
  Fixed by: Gemini
*/

#include <WiFi.h>
#include <PubSubClient.h>

// ==========================================
// 1. KONFIGURASI WIFI & MQTT
// ==========================================
// Menggunakan Hotspot HP (Pilihan tepat untuk menghindari login kampus yang ribet)
const char* ssid = "Lapphone's S24 Ultra"; 
const char* password = "caez0408";

const char* mqtt_server = "broker.emqx.io";
const char* mqtt_topic = "upn/caezar/motor/control";     // Topic Slider (HP -> ESP32)
const char* mqtt_pub_topic = "upn/caezar/motor/rpm_reading"; // Topic RPM (ESP32 -> HP)

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
const int resolution = 8; // 8-bit artinya nilai PWM 0 - 255

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
// Tuning PID: Jika respons lambat, naikkan Kc. Jika bergetar, turunkan.
float Kc = 0.007;       
float tauI = 1;         
float tauD = 1;         

float sp = 0;           // SETPOINT (Target RPM dari MQTT)
float pv = 0;           // PROCESS VALUE (RPM Asli dari Sensor)
float pv_last = 0;
float ierr = 0;
float dt = 0;
int op = 0;             // Output PWM (0-255)

unsigned long ts = 0, new_ts = 0;

// Interrupt Service Routine
void IRAM_ATTR isr() {
  rev++;
}

// ==========================================
// 4. FUNGSI WIFI & MQTT
// ==========================================
void setup_wifi() {
  delay(10);
  Serial.print("\nConnecting to ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi connected. IP: ");
  Serial.println(WiFi.localIP());
}

void callback(char* topic, byte* message, unsigned int length) {
  String msg = "";
  for (int i = 0; i < length; i++) {
    msg += (char)message[i];
  }
  msg.trim();
  
  Serial.print("MQTT Received: ");
  Serial.println(msg);

  if (msg == "stop" || msg == "off") {
    sp = 0; 
    Serial.println("Target RPM: 0 (STOP)");
  } 
  else {
    float new_sp = msg.toFloat();
    // Batas aman input target RPM
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
    String clientId = "ESP32_Caezar_" + String(random(0xffff), HEX);
    
    if (client.connect(clientId.c_str())) {
      Serial.println("connected");
      client.subscribe(mqtt_topic); 
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

  if (time_elapsed >= 1000) { 
    noInterrupts();
    unsigned long current_rev = rev;
    interrupts();
    
    int holes = 2; 
    float rotations = (float)(current_rev - last_rev_count) / holes;
    rpm = (rotations * 60000.0) / time_elapsed;
    
    rpm_filtered = 0.7 * rpm_filtered + 0.3 * rpm;
    
    last_rev_count = current_rev;
    last_rpm_time = current_time;
  }
}

float run_pid(float sp, float pv, float dt) {
  float KP = Kc;
  float KI = Kc / tauI;
  float KD = Kc * tauD; 
  
  // PERBAIKAN PENTING:
  // Output PID adalah PWM (0-255), BUKAN RPM.
  // Jadi batasnya harus 255 sesuai resolusi 8-bit.
  float ophi = 255; 
  float oplo = 0;   
  
  float error = sp - pv;
  
  ierr = ierr + KI * error * dt;
  float dpv = (pv - pv_last) / dt;
  
  float P_term = KP * error;
  float I_term = ierr;
  float D_term = -KD * dpv;
  
  float output = P_term + I_term + D_term;
  
  if ((output < oplo) || (output > ophi)) {
    ierr = ierr - KI * error * dt; 
    output = constrain(output, oplo, ophi);
  }
  
  return output;
}

// ==========================================
// 6. SETUP & LOOP
// ==========================================
void setup() {
  Serial.begin(115200);
  
  pinMode(motor1Pin1, OUTPUT);
  pinMode(motor1Pin2, OUTPUT);
  pinMode(enable1Pin, OUTPUT);
  pinMode(pin_rpm, INPUT_PULLUP);
  
  attachInterrupt(digitalPinToInterrupt(pin_rpm), isr, RISING);

  ledcSetup(pwmChannel, freq, resolution);
  ledcAttachPin(enable1Pin, pwmChannel);
  
  digitalWrite(motor1Pin1, HIGH);
  digitalWrite(motor1Pin2, LOW);

  setup_wifi();
  client.setServer(mqtt_server, 1883);
  client.setCallback(callback);
  
  ts = millis();
  last_rpm_time = millis();
}

void loop() {
  // 1. MQTT Handler
  if (!client.connected()) {
    reconnect();
  }
  client.loop();

  new_ts = millis();
  
  // 2. Baca RPM
  calculateRPM();
  
  // 3. Jalankan PID & Kirim Data (Setiap 1 detik)
  if (new_ts - ts >= 1000) { 
    pv = rpm; // Menggunakan RPM aktual
    dt = (new_ts - ts) / 1000.0;
    ts = new_ts;
    
    // Hitung PID
    op = run_pid(sp, pv, dt);
    
    // Terapkan PWM ke motor
    ledcWrite(pwmChannel, op);
    pv_last = pv;
    
    // Debug ke Serial
    Serial.print("Target: "); Serial.print(sp);
    Serial.print(" | Actual RPM: "); Serial.print(rpm);
    Serial.print(" | PWM Out: "); Serial.println(op);

    // Kirim ke HP
    String dataRPM = String(rpm); 
    client.publish(mqtt_pub_topic, dataRPM.c_str()); 
  }
  
  delay(10); 
}