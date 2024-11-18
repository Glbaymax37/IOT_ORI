#include <Adafruit_Fingerprint.h>
#include <SoftwareSerial.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

SoftwareSerial mySerial(D7, D8);  // D7 = RX, D8 = TX
Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);

const char* ssid = "Pro";         
const char* password = "12345678"; 

const char* serverUrl = "http://192.168.80.83/fingerprint/finger.php";  

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

void enrollFingerprint(int id) {
  Serial.print("Mendaftarkan sidik jari dengan ID: ");
  Serial.println(id);
  
  // Pendaftaran sidik jari pertama
  if (getFingerprintEnroll(id) == FINGERPRINT_OK) {
    Serial.println("Sidik jari berhasil didaftarkan. Tunggu perintah untuk pendaftaran berikutnya.");
  } else {
    Serial.println("Pendaftaran sidik jari gagal.");
  }
}

uint8_t getFingerprintEnroll(uint8_t id) {
  int p = -1;
  Serial.println("Tempatkan jari pada sensor.");

  // Pemindaian sidik jari pertama
  while (p != FINGERPRINT_OK) {
    p = finger.getImage();
    if (p == FINGERPRINT_NOFINGER) {
      Serial.print(".");
    } else if (p == FINGERPRINT_OK) {
      Serial.println("Sidik jari terdeteksi.");
    }
  }

  p = finger.image2Tz(1);
  if (p != FINGERPRINT_OK) {
    Serial.println("Gagal mengambil template pertama.");
    return p;
  }

  Serial.println("Lepaskan jari dan tempelkan kembali.");
  delay(2000);  

  // Pemindaian sidik jari kedua
  Serial.println("Tempelkan kembali jari untuk pemindaian kedua.");
  p = -1;  
  delay(1000);  

  while (p != FINGERPRINT_OK) {
    p = finger.getImage();
    if (p == FINGERPRINT_NOFINGER) {
      Serial.print(".");
    } else if (p == FINGERPRINT_OK) {
      Serial.println("Sidik jari kedua terdeteksi.");
    }
  }

  p = finger.image2Tz(2);
  if (p != FINGERPRINT_OK) {
    Serial.println("Gagal mengambil template kedua.");
    return p;
  }

  p = finger.createModel();
  if (p != FINGERPRINT_OK) {
    Serial.println("Gagal menggabungkan template.");
    return p;
  }

  p = finger.storeModel(id);
  if (p == FINGERPRINT_OK) {
    Serial.println("Sidik jari berhasil disimpan.");
  } else {
    Serial.println("Gagal menyimpan sidik jari di sensor.");
  }

  return p;
}

void sendTemplateToServer(uint8_t *templateData, size_t dataSize, uint8_t userId, String userName) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;

    String templateHex = "";
    for (size_t i = 0; i < dataSize; i++) {
      templateHex += String(templateData[i], HEX);
    }

    WiFiClient client;
    http.begin(client, serverUrl);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    // Kirim template dan nama pemilik ke server
    String postData = "template=" + templateHex + "&user_id=" + String(userId) + "&user_name=" + userName;

    int httpResponseCode = http.POST(postData);

    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println("Response dari server: " + response);
    } else {
      Serial.println("Gagal mengirim data ke server, kode error: " + String(httpResponseCode));
    }

    http.end();
  } else {
    Serial.println("WiFi tidak tersambung.");
  }
}
void getFingerprintTemplate(int id) {
  uint8_t templateData[512];  // Buffer untuk template

  int p = finger.loadModel(id);  // Muat template berdasarkan ID
  if (p != FINGERPRINT_OK) {
    Serial.println("Gagal memuat template.");
    return;
  }

  p = finger.getModel();  // Ambil template dari sensor
  if (p == FINGERPRINT_OK) {
    Serial.println("Template berhasil diambil.");

    // Minta nama pemilik untuk template
    String userName = "";
    Serial.println("Masukkan nama pemilik untuk template:");
    
    while (userName.length() == 0) {
      while (!Serial.available());  // Tunggu sampai ada input dari pengguna
      userName = Serial.readStringUntil('\n');  // Ambil input nama hingga newline
      userName.trim();  // Menghapus spasi kosong di awal dan akhir
    }

    sendTemplateToServer(templateData, sizeof(templateData), id, userName);  // Kirim template dan nama pemilik ke server
  } else {
    Serial.println("Gagal mengambil template.");
  }
}

void loop() {
  // Cek status koneksi WiFi secara berkala
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("WiFi terputus! Mencoba menghubungkan ulang...");
    connectToWiFi();  // Hubungkan ulang jika terputus
  }

  // Memeriksa apakah ada input dari serial monitor
  if (Serial.available()) {
    char input = Serial.read();
    
      // Jika 'r' diketik di serial monitor, pendaftaran sidik jari dimulai
      if (input == 'r') {
        int id = -1;
        // Input ID
        while (id == -1) {
          Serial.println("Masukkan ID sidik jari yang ingin didaftarkan (contoh: 1):");
          while (!Serial.available());  // Tunggu input ID dari pengguna
          id = Serial.parseInt();       // Ambil input ID
  
          if (id <= 0) {  // Jika ID tidak valid, minta ulang
            Serial.println("ID tidak valid. Harap masukkan ID yang lebih besar dari 0.");
            id = -1;  // Ulangi sampai mendapatkan input ID yang valid
          }
        }
  
        enrollFingerprint(id);  // Mendaftarkan sidik jari dengan ID yang dipilih pengguna
      }
  
      // Jika 's' diketik, ambil dan kirim template ke server
      if (input == 's') {
        int id = -1;
        while (id == -1) {
          Serial.println("Masukkan ID sidik jari untuk mengirim template (contoh: 1):");
          while (!Serial.available());  // Tunggu input ID dari pengguna
          id = Serial.parseInt();       // Ambil input ID
  
          if (id <= 0) {  // Jika ID tidak valid, minta ulang
            Serial.println("ID tidak valid. Harap masukkan ID yang lebih besar dari 0.");
            id = -1;  // Ulangi sampai mendapatkan input ID yang valid
          }
        }
        getFingerprintTemplate(id);  // Ambil template dengan ID yang dipilih pengguna
      }
  }
}
