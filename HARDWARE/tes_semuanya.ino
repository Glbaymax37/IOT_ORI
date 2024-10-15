#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <EEPROM.h>
#include <PZEM004Tv30.h>
#include <DHT.h>
#include <LiquidCrystal_I2C.h>

#define CNT_PIN 14  // Pin D5
#define DHTPIN 2 // Pin D4
#define DHTTYPE DHT11
#define SSR_PIN 12 // Pin D6

const char* ssid = "Pro";
const char* password = "12345678";
const char* server_url = "http://192.168.145.223";  // Ubah ini ke URL server Anda

PZEM004Tv30 pzem(13, 15); // D7, D8 for Pzem-004T
DHT dht(DHTPIN, DHTTYPE);
LiquidCrystal_I2C lcd(0x27, 16, 2);
WiFiClient wifiClient;
HTTPClient http;

float Dia = 0.03286;  // Diameter roda counter (dalam meter)
int CPR = 10;  // Jumlah pickup pada roda counter
float FullRoll = 330.0;  // Panjang standar satu spool filament (dalam meter)
float Remaining = 330.0;  // Panjang filament yang tersisa
float Used = 0.0, OldUsed = 0.0;
float LowLevelLimit = 5.0;  // Batas panjang filament rendah

long newPosition = 0;
long oldPosition = -999;
long Click = 0;
unsigned long LowTime = 0;
unsigned long LowLevelAlarm = 0;

bool ssrState = false; // Status SSR, false = OFF, true = ON
bool Saved = false;
bool Started = false;
bool Low = false;
bool LowLevel = false;
bool LevelLimit = false;

void DrawHeader() {
  lcd.clear();
  lcd.setCursor(3, 0);
  lcd.print("Mesin 3D Print");
}

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, password);
  pinMode(CNT_PIN, INPUT_PULLUP);
  pinMode(SSR_PIN, OUTPUT);
  digitalWrite(SSR_PIN, LOW);
  dht.begin();
  lcd.init();  // Inisialisasi layar LCD
  lcd.backlight();  // Nyalakan backlight LCD
  DrawHeader();
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    float tegangan = pzem.voltage();
    float arus = pzem.current();
    float daya = pzem.power();
    float energi = pzem.energy();
    float suhu = dht.readTemperature();
    float kelembapan = dht.readHumidity();

    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Tegangan: ");
    lcd.print(tegangan);
    lcd.print(" V");
    lcd.setCursor(0, 1);
    lcd.print("Arus: ");
    lcd.print(arus);
    lcd.print(" A");
    delay(2000);

    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Energi: ");
    lcd.print(energi);
    lcd.print(" kWh");
    delay(2000);

    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Suhu: ");
    lcd.print(suhu);
    lcd.print(" C");
    lcd.setCursor(0, 1);
    lcd.print("Kelembapan: ");
    lcd.print(kelembapan);
    lcd.print(" %");
    delay(2000);

    Serial.print("Tegangan: ");
    Serial.print(tegangan);
    Serial.println(" V");
    Serial.print("Arus: ");
    Serial.print(arus);
    Serial.println(" A");
    Serial.print("Daya: ");
    Serial.print(daya);
    Serial.println(" W");
    Serial.print("Energi: ");
    Serial.print(energi);
    Serial.println(" kWh");
    Serial.print("Suhu: ");
    Serial.print(suhu);
    Serial.println(" C");
    Serial.print("Kelembapan: ");
    Serial.print(kelembapan);
    Serial.println(" %");

    if ((Used - OldUsed) > 1.0) {
      OldUsed = Used;
      EEPROM.put(0, Remaining);
    }
    // Jika sinyal rendah, mulai pengukuran waktu
    if (digitalRead(CNT_PIN) == LOW) {
      LowTime = millis();
      Low = true;
    }
    // Jika sinyal menjadi tinggi, hitung sebagai hit
    if (Low && (digitalRead(CNT_PIN) == HIGH)) {
      if (millis() - LowTime > 1000) {
        Started = true;
        Low = false;
        LowTime = millis();
        Click++;
        Used += (Dia * 3.1415927) / CPR;  // Perhitungan dalam meter
        Remaining -= (Dia * 3.1415927) / CPR;  // Mengurangi panjang filament yang tersisa
        lcd.clear();
        lcd.setCursor(0, 1);
        lcd.print("Used: ");
        lcd.print(Used, 2);
        lcd.print(" m");
        delay(2000);
        lcd.clear();
        lcd.setCursor(0, 1);
        lcd.print("Sisa Filament: ");
        lcd.print(Remaining, 2);
        lcd.print(" m");
        delay(2000);
        // Menampilkan data pada Serial Monitor
        Serial.print("Panjang Filament yang Digunakan: ");
        Serial.print(Used, 2);
        Serial.println(" meter");
        Serial.print("Panjang Filament yang Tersisa: ");
        Serial.print(Remaining, 2);
        Serial.println(" meter");
      }
    }

    if (!isnan(tegangan) && !isnan(arus) && !isnan(daya) && !isnan(energi) && !isnan(suhu) && !isnan(kelembapan)) {
      String postData = "tegangan=" + String(tegangan) + "&arus=" + String(arus) + "&daya=" + String(daya) + "&energi=" + String(energi) + "&suhu=" + String(suhu) + "&kelembapan=" + String(kelembapan) + "&used=" + String(Used, 2) + "&remaining=" + String(Remaining, 2);

      Serial.println("POST Data: " + postData);  // Debug data yang dikirim

      http.begin(wifiClient, String(server_url) + "/IOT-SIMALAS/IOT-SIMALAS/HARDWARE/submit_data.php");
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");
      int httpResponseCode = http.POST(postData);

      Serial.print("HTTP Response Code: ");
      Serial.println(httpResponseCode);  // Debug status code

      if (httpResponseCode > 0) {
        String response = http.getString();
        Serial.println("Response: " + response);
      } else {
        Serial.println("Error on sending POST: " + String(httpResponseCode));
      }
      http.end();
    }

    // Fetch the SSR status and handle the timer from the server
    http.begin(wifiClient, String(server_url) + "/iot/get_ssr_status.php");
    int httpResponseCode = http.GET();
    if (httpResponseCode > 0) {
      String ssrStatus = http.getString();
      if (ssrStatus == "ON") {
        digitalWrite(SSR_PIN, HIGH);  // Activate SSR
      } else {
        digitalWrite(SSR_PIN, LOW);  // Deactivate SSR
      }
    }
    http.end();

  } // End of WiFi connected block

  delay(1000);  // Wait for 1 seconds before the next loop iteration
}
