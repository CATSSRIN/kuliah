/*
  iMCLab Internet-Based Motor Control Lab
  Kit internet-Based Motor Control Lab (iMCLab), adalah modul
  hardware kendali motor DC umpan balik dengan mikrokontroller
  ESP32, yang terdiri dari motor DC, driver motor, LED, dan
  sensor kecepatan. Kit ini dikembangkan oleh Kampus Bela
  Negara. Keluaran kecepatan motor DC dalam rotasi per menit
  (rpm) disesuaikan untuk mempertahankan setpoint rpm yang
  diinginkan. Kit ini dikembangkan oleh tim kami, sebagai
  salah satu luaran tambahan dari Penelitian Terapan Unggulan
  Perguruan Tinggi (PTUPT) Tahun Kedua.
*/

// constants
const int baud = 115200;       // serial baud rate

int motor1Pin1 = 27;
int motor1Pin2 = 26;
int enable1Pin = 12;

//int pulse_encoder = 13;

// Setting PWM properties
const int freq = 30000;
const int pwmChannel = 0;
const int resolution = 8;
//int dutyCycle = 0;

const byte pin_rpm = 13;
int volatile rev = 0;
int rpm = 0;
int rpm1 = 0;

unsigned long cur_time, old_time;

float P, I, D, Kc, tauI, tauD;
float KP, KI, KD, op0, ophi, oplo, error, dpv;
float sp = 100, //set point
pv = 0,        //current temperature
pv_last = 0,   //prior temperature
ierr = 0,      //integral error
dt = 0,        //time between measurements
op = 0;        //PID controller output
unsigned long ts = 0, new_ts = 0; //timestamp
const float upper_temperature_limit = 58;

void isr() {
  rev++;
}

void setup() {
  // put your setup code here, to run once:

  ts = millis();
  
  Serial.begin(baud); 
  while (!Serial) {
    ; // wait for serial port to connect.
  }
  
  // sets the pins
  pinMode(motor1Pin1, OUTPUT);
  pinMode(motor1Pin2, OUTPUT);
  pinMode(enable1Pin, OUTPUT);
  pinMode(pin_rpm, INPUT_PULLUP);
  //  pinMode(pin_rpm, INPUT);
  attachInterrupt(digitalPinToInterrupt(pin_rpm), isr, RISING);

  // configure LED PWM functionalitites
  ledcSetup(pwmChannel, freq, resolution);

  // attach the channel to the GPIO to be controlled
  ledcAttachPin(enable1Pin, pwmChannel);

  // testing
  Serial.print("Testing DC Motor...");
}

void CekRPM(){
  // Move DC motor forward 
  digitalWrite(motor1Pin1, HIGH);
  digitalWrite(motor1Pin2, LOW);
  ledcWrite(pwmChannel, op);
  detachInterrupt(digitalPinToInterrupt(pin_rpm)); //mematikan interupt
  int holes = 2; // holes of rotating object, for disc object 
  int rpm = rev / holes;
  int rpm1 = rpm * 60;
  //Serial.println(rev);
  //here we used fan which has 2 hole
  Serial.print("Forward with duty cycle: ");
  Serial.println(op);
  Serial.print("Rot/sec :");  //Revolutions per second
  Serial.println(rpm);
  Serial.print("Rot/min : ");   //Revolutions per minute
  Serial.println((rpm1));
  rev = 0;
  attachInterrupt(digitalPinToInterrupt(pin_rpm), isr, RISING); // Menghidupkan interupt

}


float pid(float sp, float pv, float pv_last, float& ierr, float dt) {
  float Kc = 10.0; // K / %Heater
  float tauI = 50.0; // sec
  float tauD = 1.0;  // sec
  // PID coefficients
  float KP = Kc;
  float KI = Kc / tauI;
  float KD = Kc*tauD; 
  // upper and lower bounds on heater level
  float ophi = 100;
  float oplo = 0;
  // calculate the error
  float error = sp - pv;
  // calculate the integral error
  ierr = ierr + KI * error * dt;  
  // calculate the measurement derivative
  float dpv = (pv - pv_last) / dt;
  // calculate the PID output
  float P = KP * error; //proportional contribution
  float I = ierr; //integral contribution
  float D = -KD * dpv; //derivative contribution
  float op = P + I + D;
  // implement anti-reset windup
  if ((op < oplo) || (op > ophi)) {
    I = I - KI * error * dt;
    // clip output
    op = max(oplo, min(ophi, op));
  }
  ierr = I; 
  Serial.println("sp="+String(sp) + " pv=" + String(pv) + " dt=" + String(dt) + " op=" + String(op) + " P=" + String(P) + " I=" + String(I) + " D=" + String(D));
  return op;
}

void loop() {

  new_ts = millis();
  if (new_ts - ts > 1000) {   

  // put your main code here, to run repeatedly:
  CekRPM();

  pv = rpm1;   // RPM keluaran
  dt = (new_ts - ts) / 1000.0;
  ts = new_ts;
  op = pid(sp,pv,pv_last,ierr,dt);
  //ledcWrite(Q1Channel,op); Contoh yg iTCLab
  ledcWrite(pwmChannel, op);

  pv_last = pv;
  
  delay (100);
  }

}
