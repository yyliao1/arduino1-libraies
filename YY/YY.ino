---------------------------------------------------------------------------------------------------------
* MFRC522 esp8266
* -----------------------------------------------------------------------------------------------------------
*RST GPIO15
*SDA(SS) GPIO2 
*MOSI GPIO13
*MISO GPIO12
*SCK GPIO14
*GND GND
*3,3V 3,3V
* -----------------------------------------------------------------------------------------------------------
With this small example you should be able to connect to your WiFi Network and read a Rfid-Card (Output of the UID of the Card check on Serial):

#include <ESP8266WiFi.h>
#include <SPI.h>
#include "MFRC522.h"

#define RST_PIN  15 // RST-PIN für RC522 - RFID - SPI - Modul GPIO15 
#define SS_PIN  2 // SDA-PIN für RC522 - RFID - SPI - Modul GPIO2 

const char *ssid =  "YYap"; // change according to your Network - cannot be longer than 32 characters!
const char *pass =  "1111111111"; // change according to your Network

MFRC522 mfrc522(SS_PIN, RST_PIN); // Create MFRC522 instance

void setup() {
Serial.begin(9600); // Initialize serial communications
delay(250);
Serial.println(F("Booting...."));

SPI.begin();  // Init SPI bus
mfrc522.PCD_Init(); // Init MFRC522

WiFi.begin(ssid, pass);

int retries = 0;
while ((WiFi.status() != WL_CONNECTED) && (retries < 10)) {
retries++;
delay(500);
Serial.print(".");
}
if (WiFi.status() == WL_CONNECTED) {
Serial.println(F("WiFi connected"));
}

Serial.println(F("Ready!"));
Serial.println(F("======================================================")); 
Serial.println(F("Scan for Card and print UID:"));
}

void loop() { 
// Look for new cards
if ( ! mfrc522.PICC_IsNewCardPresent()) {
delay(50);
return;
}
// Select one of the cards
if ( ! mfrc522.PICC_ReadCardSerial()) {
delay(50);
return;
}
// Dump debug info about the card; PICC_HaltA() is automatically called
mfrc522.PICC_DumpToSerial(&(mfrc522.uid));
Serial.println(F("======================================================"));
} - See more at: http://www.esp8266.com/viewtopic.php?f=13&t=3435&sid=751b1e26e769dc98e890b5a66d825493&start=4#sthash.VVAjEYXK.dpuf
