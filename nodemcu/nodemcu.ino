#include <SoftwareSerial.h>
//library wifi
#include <ESP8266HTTPClient.h>
#include <ESP8266WiFi.h>

//Network SSID
const char* ssid = "empty";
const char* password = "fajri1001";

//pengenal host (server) = IP Address komputer server
const char* host = "192.168.43.118";

SoftwareSerial node(5, 4); // RX, TX

String kirimMega = "";
String modeWeb = "";
String dataMega = "";
String arrMega[3];
int indexMega = 0;
String payload = "";

void setup() {
  // put your setup code here, to run once:
  Serial.begin(9600);
  node.begin(9600);

//  //setting koneksi wifi
  WiFi.mode(WIFI_STA);
  WiFi.hostname("NodeMCU");
  WiFi.begin(ssid, password);

  //cek koneksi wifi
  while(WiFi.status() != WL_CONNECTED)
  {
    //progress sedang mencari WiFi
    delay(500);
    Serial.print(".");
  }
  
  Serial.println("Wifi Connected");
  Serial.println("IP Address : ");
  Serial.println(WiFi.localIP());
  
}

void loop() {
  modeWeb = prosesBacaModeWeb();
  kirimMega = modeWeb;
//  Serial.println(kirimMega);
  node.println(kirimMega);
  dataMega = bacaDataMega();
  if(dataMega != ""){
    Serial.print("data = ");
    Serial.println(dataMega);
    for(int i = 0; i <= dataMega.length(); i++){
      char delimiter = '#';
      if(dataMega[i] != delimiter){
        arrMega[indexMega] += dataMega[i];    
      }else{
        indexMega++;
      }
    }
    Serial.println(arrMega[0]);
    Serial.println(arrMega[1]);
    Serial.println(arrMega[2]);
    
    String Link;
    HTTPClient http;
    //ip sesuaikan dengan ip wifi
    if(arrMega[2] == "daftar"){
      Link = "http://192.168.43.118/iqbal/kirimkartu.php?nokartu=" + arrMega[0] + "&finger=" + arrMega[1];  
    }else{
      Link = "http://192.168.43.118/iqbal/kirimkartuTransaksi.php?nokartu=" + arrMega[0] + "&finger=" + arrMega[1];
    }
    http.begin(Link);

    int httpCode = http.GET();
    payload = http.getString();
    Serial.println(payload);
    kirimMega = payload;
    node.println(kirimMega);
    
    delay(1000);    
    http.end();
    
  }
  
  delay(2000);
  arrMega[0] = "";
  arrMega[1] = "";
  arrMega[2] = "";
  dataMega = "";
  payload = "";
  indexMega = 0;
  
  
}

String prosesBacaModeWeb(){
  String Link;
  HTTPClient http;
  Link = "http://192.168.43.118/iqbal/bacaMode.php";
  http.begin(Link);

  int httpCode = http.GET();
  String payload = http.getString();
//  client.stop();
  http.end();
  return payload;
}

String bacaDataMega(){
  while(node.available() > 0) {
    dataMega += char(node.read());
  }
  dataMega.trim();
  
  return dataMega;
}
