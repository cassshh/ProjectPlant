#include <SPI.h>
#include <WiFly.h>
#include <OneWire.h>
#include <DallasTemperature.h>

#include "Credentials.h"
WiFlyClient client("casnetwork.tk", 80);

#define ONE_WIRE_BUS A2
#define TEMPERATURE_PRECISION 12

OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);
DeviceAddress tempDeviceAddress; 

  int numberOfDevices; 
  
  int moistPin = 3;     
  int moistReading; 
  
  int photocellPin = 0;     
  int photocellReading;
  
  
  long prev = 0;
  long inter = 10000;
  
  double tempReading;
  


void setup() {
   Serial.begin(9600); 
    sensors.begin();
    numberOfDevices = sensors.getDeviceCount();
    
    WiFly.begin();
  
  if (!WiFly.join(ssid, passphrase)) {
    Serial.println("Ssid of wachtwoord verkeerd.");
    while (1) {
      // Hang on failure.
    }
  }  
 }

double printTemperature(DeviceAddress deviceAddress)
{
  float tempC = sensors.getTempC(deviceAddress);
  //  Serial.print("Temp C: ");
  //Serial.println(tempC);
  return (double) tempC;
}



void loop() {
  
  unsigned long current = millis();
  //if(current - prev > inter)
  //{

    //prev = current;
    
    moistReading = analogRead(moistPin); 
    photocellReading = analogRead(photocellPin); 

    
    sensors.requestTemperatures(); 
      
      for(int i=0;i<numberOfDevices; i++)
      {
        if(sensors.getAddress(tempDeviceAddress, i))
        {
    		tempReading = printTemperature(tempDeviceAddress);
    	} 
    
    	
      }
    Serial.println();
  
 //}
  
  if(client.connect())
  {
        client.print("GET /plant/addData.php");
        client.print("?temp=");
        client.print(tempReading);
        client.print("&moist=");
        client.print(moistReading);
        client.print("&light=");
        client.print(photocellReading);
        client.println( "HTTP/1.1");
        client.println( "Host: casnetwork.tk" );
        client.println( "Content-Type: application/x-www-form-urlencoded" );
        client.println( "Connection: close" );
        client.println();
        client.println();
        client.stop();
  }
  
delay(43000);
  
  
  
  
}
