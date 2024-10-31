#include <Adafruit_Fingerprint.h>
#include <sha256.h>
#include <SoftwareSerial.h>  // Include SoftwareSerial library

#if (defined(__AVR__) || defined(ESP8266)) && !defined(__AVR_ATmega2560__)
// For UNO and others without hardware serial
// pin #2 is IN from sensor (GREEN wire)
// pin #3 is OUT from Arduino (WHITE wire)
SoftwareSerial mySerial(2, 3);  // Use SoftwareSerial for UNO and similar boards

#else
// For Leonardo/M0/etc, use hardware serial
#define mySerial Serial1

#endif

Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);
Sha256 hash;

uint8_t id;

void setup()
{
  Serial.begin(9600);
  while (!Serial);  // Wait for Serial to initialize
  delay(100);
  Serial.println("\n\nAdafruit Fingerprint sensor enrollment");

  // Set the data rate for the sensor serial port
  finger.begin(57600);

  if (finger.verifyPassword()) {
    Serial.println("Found fingerprint sensor!");
  } else {
    Serial.println("Did not find fingerprint sensor :(");
    while (1) { delay(1); }
  }

  Serial.println(F("Reading sensor parameters"));
  finger.getParameters();
  Serial.print(F("Status: 0x")); Serial.println(finger.status_reg, HEX);
  Serial.print(F("Sys ID: 0x")); Serial.println(finger.system_id, HEX);
  Serial.print(F("Capacity: ")); Serial.println(finger.capacity);
  Serial.print(F("Security level: ")); Serial.println(finger.security_level);
  Serial.print(F("Device address: ")); Serial.println(finger.device_addr, HEX);
  Serial.print(F("Packet len: ")); Serial.println(finger.packet_len);
  Serial.print(F("Baud rate: ")); Serial.println(finger.baud_rate);
}

uint8_t readnumber(void) {
  uint8_t num = 0;

  while (num == 0) {
    while (!Serial.available());
    num = Serial.parseInt();
  }
  return num;
}

void loop() {
  Serial.println("Ready to enroll a fingerprint!");
  Serial.println("Please type in the ID # (from 1 to 127) you want to save this finger as...");
  id = readnumber();
  if (id == 0) { // ID #0 not allowed, try again!
    return;
  }
  Serial.print("Enrolling ID #");
  Serial.println(id);

  while (!getFingerprintEnroll());

  // Convert ID to hash
  String hashedId = hashId(id);
  Serial.print("Hashed ID: ");
  Serial.println(hashedId);
}

uint8_t getFingerprintEnroll() {
  int p = -1;
  Serial.print("Waiting for valid finger to enroll as #"); Serial.println(id);
  while (p != FINGERPRINT_OK) {
    p = finger.getImage();
    switch (p) {
      case FINGERPRINT_OK:
        Serial.println("Image taken");
        break;
      case FINGERPRINT_NOFINGER:
        Serial.print(".");
        break;
      case FINGERPRINT_PACKETRECIEVEERR:
        Serial.println("Communication error");
        break;
      case FINGERPRINT_IMAGEFAIL:
        Serial.println("Imaging error");
        break;
      default:
        Serial.println("Unknown error");
        break;
    }
  }

  // OK success!
  p = finger.image2Tz(1);
  if (p != FINGERPRINT_OK) {
    handleImageConversionError(p);
    return p;
  }

  Serial.println("Remove finger");
  delay(2000);
  p = 0;
  while (p != FINGERPRINT_NOFINGER) {
    p = finger.getImage();
  }
  
  Serial.print("ID "); Serial.println(id);
  p = -1;
  Serial.println("Place same finger again");
  while (p != FINGERPRINT_OK) {
    p = finger.getImage();
    switch (p) {
      case FINGERPRINT_OK:
        Serial.println("Image taken");
        break;
      case FINGERPRINT_NOFINGER:
        Serial.print(".");
        break;
      case FINGERPRINT_PACKETRECIEVEERR:
        Serial.println("Communication error");
        break;
      case FINGERPRINT_IMAGEFAIL:
        Serial.println("Imaging error");
        break;
      default:
        Serial.println("Unknown error");
        break;
    }
  }

  // OK success!
  p = finger.image2Tz(2);
  if (p != FINGERPRINT_OK) {
    handleImageConversionError(p);
    return p;
  }

  // OK converted!
  Serial.print("Creating model for #");  Serial.println(id);
  p = finger.createModel();
  if (p == FINGERPRINT_OK) {
    Serial.println("Prints matched!");
  } else {
    handleModelCreationError(p);
    return p;
  }

  Serial.print("ID "); Serial.println(id);
  p = finger.storeModel(id);
  if (p == FINGERPRINT_OK) {
    Serial.println("Stored!");
  } else {
    handleStorageError(p);
    return p;
  }

  return true;
}

void handleImageConversionError(int p) {
  switch (p) {
    case FINGERPRINT_IMAGEMESS:
      Serial.println("Image too messy");
      break;
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      break;
    case FINGERPRINT_FEATUREFAIL:
      Serial.println("Could not find fingerprint features");
      break;
    case FINGERPRINT_INVALIDIMAGE:
      Serial.println("Invalid image");
      break;
    default:
      Serial.println("Unknown error");
      break;
  }
}

void handleModelCreationError(int p) {
  switch (p) {
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      break;
    case FINGERPRINT_ENROLLMISMATCH:
      Serial.println("Fingerprints did not match");
      break;
    default:
      Serial.println("Unknown error");
      break;
  }
}

void handleStorageError(int p) {
  switch (p) {
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      break;
    case FINGERPRINT_BADLOCATION:
      Serial.println("Could not store in that location");
      break;
    case FINGERPRINT_FLASHERR:
      Serial.println("Error writing to flash");
      break;
    default:
      Serial.println("Unknown error");
      break;
  }
}

String hashId(uint8_t id) {
  // Convert ID to string and perform hashing
  String idStr = String(id);
  hash.update((uint8_t *)idStr.c_str(), idStr.length());

  // Finalize the hash and get the result
  byte hashResult[SHA256_BLOCK_SIZE]; // SHA-256 outputs a 32-byte hash
  hash.final(hashResult); // Call the final method to finalize the hash

  // Convert the hash result to a hexadecimal string
  String hexHash = "";
  for (int i = 0; i < SHA256_BLOCK_SIZE; i++) {
    // Convert byte to hex and append to the result
    hexHash += String(hashResult[i], HEX);
  }

  return hexHash;
}
