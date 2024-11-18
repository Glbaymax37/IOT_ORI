#include <ArduinoJson.h>
#include <Adafruit_Fingerprint.h>
#include <SoftwareSerial.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

SoftwareSerial mySerial(D7, D8);  // D5 = RX, D6 = TX
Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);

const char* ssid = "Pro";         
const char* password = "12345678"; 
const char* serverUrl = "http://192.168.80.83/fingerprint/check.fingerprint.php";  

void setup() {
  Serial.begin(115200);
  connectToWiFi();

  finger.begin(57600);
  if (finger.verifyPassword()) {
    Serial.println("Sensor fingerprint terdeteksi.");
  } else {
    Serial.println("Sensor fingerprint tidak terdeteksi.");
    while (1);
  }
}

void connectToWiFi() {
  WiFi.begin(ssid, password);
  
  Serial.println("Menghubungkan ke WiFi...");
  int attempts = 0;
  while (WiFi.status() != WL_CONNECTED && attempts < 20) {
    delay(500);
    Serial.print(".");
    attempts++;
  }
  
  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\nWiFi terhubung!");
  } else {
    Serial.println("\nGagal terhubung ke WiFi. Coba lagi nanti.");
  }
}

void authenticateFingerprint() {
  Serial.println("Tempatkan jari pada sensor untuk autentikasi.");

  int p = -1; // Inisialisasi p dengan nilai yang tidak valid

  // Loop hingga sidik jari terdeteksi
  while (p != FINGERPRINT_OK) {
    p = finger.getImage(); // Coba ambil gambar sidik jari
    if (p == FINGERPRINT_NOFINGER) {
      Serial.print("."); // Tampilkan titik sebagai indikasi bahwa tidak ada jari
      delay(500); // Delay untuk menghindari pemanggilan terlalu cepat
    } else if (p == FINGERPRINT_OK) {
      Serial.println("Sidik jari terdeteksi. Mengambil template dari server...");

      // Lakukan permintaan HTTP untuk mendapatkan semua template dan username
      HTTPClient http;
      WiFiClient client;
      String url = String(serverUrl) + "?all_templates=1"; // Mengambil semua template
      http.begin(client, url);
      int httpResponseCode = http.GET();

      if (httpResponseCode > 0) {
        String response = http.getString(); // Ambil respons dari server
        DynamicJsonDocument doc(1024);
        deserializeJson(doc, response); // Parse JSON

        // Loop melalui setiap user yang ada
        for (JsonObject user : doc.as<JsonArray>()) {
          int userId = user["user_id"];
          String templateHex = user["template"];
          String username = user["username"];

          // Konversi string hex kembali menjadi array byte
          uint8_t templateData[512]; // Sesuaikan ukuran jika perlu
          for (size_t i = 0; i < templateHex.length(); i += 2) {
            String byteString = templateHex.substring(i, i + 2);
            templateData[i / 2] = (uint8_t) strtol(byteString.c_str(), nullptr, 16);
          }

          // Muat template ke dalam sensor sidik jari
          if (finger.loadModel(userId) == FINGERPRINT_OK) {
            Serial.println("Template berhasil dimuat.");

            // Autentikasi sidik jari
            int result = finger.fingerFastSearch();
            if (result == FINGERPRINT_OK) {
              Serial.println("Sidik jari terautentikasi.");
              Serial.print("User ID: ");
              Serial.println(userId); // Tampilkan User ID
              Serial.print("Username: ");
              Serial.println(username); // Tampilkan Username
              return; // Berhenti setelah autentikasi berhasil
            }
          } else {
            Serial.println("Gagal memuat template.");
          }
        }
        Serial.println("Sidik jari tidak terautentikasi.");
      } else {
        Serial.println("Gagal mendapatkan data dari server.");
      }
      http.end();
    }
  }
}

void loop() {
  authenticateFingerprint(); // Langsung autentikasi sidik jari di loop
  delay(2000); // Delay agar tidak terlalu cepat
}
