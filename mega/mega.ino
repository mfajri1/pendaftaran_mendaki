#include <Adafruit_Fingerprint.h>
#include <LiquidCrystal_I2C.h>
#include <MFRC522.h>
#include <SPI.h>
#include <Servo.h>
#include <SoftwareSerial.h>

LiquidCrystal_I2C lcd(0x27, 16, 2);

#define RST_PIN         5
#define SS_PIN          53

SoftwareSerial sMega(11, 10);
SoftwareSerial sFinger(62, 63);
Adafruit_Fingerprint finger = Adafruit_Fingerprint(&sFinger);


//konfig rfid
MFRC522 mfrc522(SS_PIN, RST_PIN);

// inisiasi servo
Servo servo1;
uint8_t id;
int pos = 0;
int data = 0;
String dataNode = "";
String IDTAG = "";
String kirim = "";
int idFinger = 0;
void setup() {
  // put your setup code here, to run once:
  Serial.begin(9600);
  Serial1.begin(9600);
  SPI.begin();
  lcd.begin();
  lcd.backlight();
  lcd.clear();
  while (!Serial);  // For Yun/Leo/Micro/Zero/...
  delay(100);
  Serial.println("\n\nAdafruit Fingerprint sensor enrollment");

  // set the data rate for the sensor serial port
  finger.begin(57600);
  delay(5);

  if (finger.verifyPassword()) {
    tampilLCD("Fingerprint", "Ditemukan", "");
    delay(1000);
  } else {
    tampilLCD("Fingerprint", "Tidak Ditemukan", "");
    while (1) { delay(1); }
  }

  delay(1000);
 
  // digunakan untuk mengambil funtion templateCount
  finger.getTemplateCount();

  if (finger.templateCount == 0) {
    Serial.print("data fingerprint tidak ada yang tersimpan");
    tampilLCD("Data Finger", "Kosong", "");
    delay(1000);
  } 
  else {
    Serial.println("Waiting for valid finger...");
    Serial.print("jumlah ID "); Serial.print(finger.templateCount); Serial.println(" templates");
    
    tampilLCD("Fingerprint", "Data = ", String(finger.templateCount));
    delay(1000);
  }
  // setting awal RFID  
  mfrc522.PCD_Init();
  tampilLCD("Silahkan Mulai", "Memilih Mode", "");
  // kosongkan data finger
//  finger.emptyDatabase();
  
}

void loop() {
  // put your main code here, to run repeatedly:
  dataNode = bacaDataNode();
  dataNode.trim();
  if(dataNode != ""){
    if(dataNode == "1"){
      tampilLCD("Mode = Standby", "Pilih Mode", "");
    }
    // Mode Daftar      
    else if(dataNode == "2"){
      tampilLCD("Mode = Daftar", "Tempelkan Kartu", "");
      // baca rfid
      if(! mfrc522.PICC_IsNewCardPresent()){
        return ;
      }
      if(! mfrc522.PICC_ReadCardSerial()){
        return ;
      }
      for(byte i=0; i<mfrc522.uid.size; i++){
        IDTAG += mfrc522.uid.uidByte[i];
      }
      if(IDTAG != ""){
        tampilLCD("Kartu Anda", "ID = ", IDTAG);
        delay(1500);
        tampilLCD("Tempelkan Jari", "", "");
        data = 1;
        finger.getTemplateCount();
        id = finger.templateCount + data;
        getFingerprintEnroll();
        delay(50);
        kirim = IDTAG + '#' + id + '#' + "daftar";
        Serial1.println(kirim);
        tampilLCD("Jari Anda", "ID Kartu = ", String(id));
        delay(1500); 
      } 
    }
    // Proses Transaksi
    else if(dataNode == "3"){
      tampilLCD("Mode = Transaksi", "Tempelkan Kartu", "");
      // baca rfid
      if(! mfrc522.PICC_IsNewCardPresent()){
        return ;
      }
      if(! mfrc522.PICC_ReadCardSerial()){
        return ;
      }
      for(byte i=0; i<mfrc522.uid.size; i++){
        IDTAG += mfrc522.uid.uidByte[i];
      }
      tampilLCD("Kartu Anda", "ID = ", String(IDTAG));
      if(IDTAG != ""){
        while(finger.confidence <80){
          idFinger = getFingerprintIDez();
          delay(50);
        }
        tampilLCD("Proses Cek ", "", "");
        finger.confidence = 0;
        kirim = "";
        kirim = IDTAG + "#" + idFinger + '#' + "trans";
        Serial1.println(kirim);
        delay(1000);
        dataNode = "";
        dataNode = bacaDataNode();
        dataNode.trim();
        tampilLCD("Hasil ", dataNode, ""); 
      }
      
    }
    dataNode = "";
    kirim = "";
    id = 0;
    idFinger = 0;
    IDTAG = ""; 
  }
  delay(500);
  
}

void tampilLCD(String data1, String data2, String data3){
  lcd.clear();
  lcd.setCursor(0,0);
  lcd.print(data1);
  lcd.setCursor(0,1);
  lcd.print(data2);
  lcd.print(data3);
}

String bacaDataNode(){
  while(Serial1.available() > 0) {
    dataNode += char(Serial1.read());
  }
  dataNode.trim();
  
  return dataNode;
}

uint8_t getFingerprintEnroll() {

  int p = -1;
  Serial.print("Waiting for valid finger to enroll as #"); Serial.println(id);
  tampilLCD("Menunggun","Jari Sesuai",""); 
  while (p != FINGERPRINT_OK) {
    p = finger.getImage();
    switch (p) {
    case FINGERPRINT_OK:
      Serial.println("Image taken");
      tampilLCD("Image Taken","","");
      break;
    case FINGERPRINT_NOFINGER:
      Serial.println(".");
      tampilLCD("Tunggu...", "","");
      break;
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      tampilLCD("Communication error", "", "");
      break;
    case FINGERPRINT_IMAGEFAIL:
      Serial.println("Imaging error");
      tampilLCD("Imaging error", "", "");
      break;
    default:
      Serial.println("Unknown error");
      tampilLCD("Unknown error", "", "");
      break;
    }
  }

  // OK success!

  p = finger.image2Tz(1);
  switch (p) {
    case FINGERPRINT_OK:
      Serial.println("Image converted");
      tampilLCD("Image Converted", "", "");
      break;
    case FINGERPRINT_IMAGEMESS:
      Serial.println("Image too messy");
      tampilLCD("Image Too Messy", "", "");
      return p;
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      tampilLCD("Communiction error", "", "");
      return p;
    case FINGERPRINT_FEATUREFAIL:
      Serial.println("Could not find fingerprint features");
      tampilLCD("Could Not Find", "Fingerprint future", "");
      return p;
    case FINGERPRINT_INVALIDIMAGE:
      Serial.println("Could not find fingerprint features");
      return p;
    default:
      Serial.println("Unknown error");
      tampilLCD("Unknown error", "", "");
      return p;
  }
  
  Serial.println("Remove finger");
  tampilLCD("Remove Finger", "", "");
  delay(2000);
  p = 0;
  while (p != FINGERPRINT_NOFINGER) {
    p = finger.getImage();
  }
  Serial.print("ID "); Serial.println(id);
  tampilLCD("", "ID = ", String(id));
  p = -1;
  Serial.println("Place same finger again");
  tampilLCD("Place Same", "Finger Again", "");
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
  switch (p) {
    case FINGERPRINT_OK:
      Serial.println("Image converted");
      break;
    case FINGERPRINT_IMAGEMESS:
      Serial.println("Image too messy");
      return p;
    case FINGERPRINT_PACKETRECIEVEERR:
      Serial.println("Communication error");
      return p;
    case FINGERPRINT_FEATUREFAIL:
      Serial.println("Could not find fingerprint features");
      return p;
    case FINGERPRINT_INVALIDIMAGE:
      Serial.println("Could not find fingerprint features");
      return p;
    default:
      Serial.println("Unknown error");
      return p;
  }
  
  // OK converted!
  Serial.print("Creating model for #");  Serial.println(id);
  
  p = finger.createModel();
  if (p == FINGERPRINT_OK) {
    Serial.println("Prints matched!");
    tampilLCD("Prints Matched", "", "");
  } else if (p == FINGERPRINT_PACKETRECIEVEERR) {
    Serial.println("Communication error");
    return p;
  } else if (p == FINGERPRINT_ENROLLMISMATCH) {
    Serial.println("Fingerprints did not match");
    tampilLCD("Print Did Not", "Match", "");
    return p;
  } else {
    Serial.println("Unknown error");
    return p;
  }   
  
  Serial.print("ID "); Serial.println(id);
  p = finger.storeModel(id);
  if (p == FINGERPRINT_OK) {
    Serial.println("Stored!");
  } else if (p == FINGERPRINT_PACKETRECIEVEERR) {
    Serial.println("Communication error");
    return p;
  } else if (p == FINGERPRINT_BADLOCATION) {
    Serial.println("Could not store in that location");
    return p;
  } else if (p == FINGERPRINT_FLASHERR) {
    Serial.println("Error writing to flash");
    return p;
  } else {
    Serial.println("Unknown error");
    return p;
  }   
}

// returns -1 if failed, otherwise returns ID #
int getFingerprintIDez() {
  uint8_t p = finger.getImage();
  if (p != FINGERPRINT_OK)  return -1;

  p = finger.image2Tz();
  if (p != FINGERPRINT_OK)  return -1;

  p = finger.fingerFastSearch();
  if (p != FINGERPRINT_OK)  return -1;
  
  // found a match!
  Serial.print("Found ID #"); Serial.print(finger.fingerID); 
  Serial.print(" with confidence of "); Serial.println(finger.confidence);
//  check();
  return finger.fingerID; 
}
