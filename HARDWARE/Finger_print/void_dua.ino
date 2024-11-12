#include <Adafruit_Fingerprint.h>

#if (defined(__AVR__) || defined(ESP8266)) && !defined(__AVR_ATmega2560__)


SoftwareSerial mySerial(2, 3);
#else
#define mySerial Serial1
#endif

Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);
int stage;

uint8_t user = 4;

void setup()
{
  while (!Serial);

  stage = 0;
  Serial.begin(9600);
  Serial.println("Fingerprint template extractor");

  finger.begin(57600);

  if (finger.verifyPassword()) {
    Serial.println("Found fingerprint sensor!");
  } else {
    Serial.println("Did not find fingerprint sensor :(");
    while (1);
  }
}

void loop()
{
  while (stage == 0) {
//    uint8_t result = downloadFingerprintTemplate(4);
//    delay(5000); 
  getFingerprintEnroll();
  delay(5000);
    stage += 1;
  }
  while (stage == 1){
    Serial.println("bergodu");
    Serial.println(user);
    uint8_t result = downloadFingerprintTemplate(4);
    delay(5000);
  }
 

}
//uint8_t readnumber(void) {
//  uint8_t num = 0;
//
//  while (num == 0) {
//    while (!Serial.available());
//    num = Serial.parseInt();
//  }
//  return num;
//}
uint8_t getFingerprintEnroll() {
  int v = -1;
  Serial.print("Waiting for valid finger to enroll as #"); Serial.println(user);
  
  while (v != FINGERPRINT_OK) {
    v = finger.getImage();
    switch (v) {
    case FINGERPRINT_OK:
      Serial.println("Image taken");
      break;
    }
  }

  v = finger.image2Tz(1);
  switch (v) {
    case FINGERPRINT_OK:
      Serial.println("Image converted");
      break;
  }

  Serial.println("Remove finger");
  delay(2000);
  v = 0;
  while (v != FINGERPRINT_NOFINGER) {
    v = finger.getImage();
  }
  
  Serial.print("ID "); Serial.println(user);
  v = -1;
  Serial.println("Place same finger again");
  
  while (v != FINGERPRINT_OK) {
    v = finger.getImage();
    switch (v) {
    case FINGERPRINT_OK:
      Serial.println("Image taken");
      break;
    }
  }

  v = finger.image2Tz(2);
  switch (v) {
    case FINGERPRINT_OK:
      Serial.println("Image converted");
      break;
  }

  Serial.print("Creating model for #");  Serial.println(user);

  v = finger.createModel();
  if (v == FINGERPRINT_OK) {
    Serial.println("Prints matched!");
  } 

  Serial.print("ID "); Serial.println(user);
  v = finger.storeModel(user);
  if (v == FINGERPRINT_OK) {
    Serial.println("Stored!");
  }

  return true;
}


uint8_t downloadFingerprintTemplate(uint16_t id)
{
  Serial.println("------------------------------------");
  Serial.print("Attempting to load #"); Serial.println(id);
  uint8_t p = finger.loadModel(id);
  switch (p) {
    case FINGERPRINT_OK:
      Serial.print("Template "); Serial.print(id); Serial.println(" loaded");
      break;
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      return p;
    default:
      Serial.print("Unknown error "); Serial.println(p);
      return p;
  }

  Serial.print("Attempting to get #"); Serial.println(id);
  p = finger.getModel();
  switch (p) {
    case FINGERPRINT_OK:
      Serial.print("Template "); Serial.print(id); Serial.println(" transferring:");
      break;
    default:
      Serial.print("Unknown error "); Serial.println(p);
      return p;
  }

  uint8_t bytesReceived[534];
  memset(bytesReceived, 0xff, 534);

  uint32_t starttime = millis();
  int i = 0;
  while (i < 534 && (millis() - starttime) < 20000) {
    if (mySerial.available()) {
      bytesReceived[i++] = mySerial.read();
    }
  }
  Serial.print(i); Serial.println(" bytes read.");
  Serial.println("Decoding packet...");

  uint8_t fingerTemplate[512];
  memset(fingerTemplate, 0xff, 512);

  int uindx = 9, index = 0;
  memcpy(fingerTemplate + index, bytesReceived + uindx, 256);
  uindx += 256;
  uindx += 2;
  uindx += 9;
  index += 256;
  memcpy(fingerTemplate + index, bytesReceived + uindx, 256);

  for (int i = 0; i < 512; ++i) {
    printHex(fingerTemplate[i], 2);
  }
  Serial.println("\ndone.");

  return p;
}

void printHex(int num, int precision) {
  char tmp[16];
  char format[128];

  sprintf(format, "%%.%dX", precision);
  sprintf(tmp, format, num);
  Serial.print(tmp);
}
