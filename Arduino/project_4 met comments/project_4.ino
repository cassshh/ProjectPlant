#include <SPI.h>    
#include <WiFly.h>  // ↑ Beide Wifly library's
#include <OneWire.h>
#include <DallasTemperature.h> // ↑ Library's voor het meten van de temperatuur 

#include "Credentials.h"  // Het bestand waar de WiFly instellingen zijn gedefinieerd. 
WiFlyClient client("casnetwork.tk", 80);  // De server waar de gemeten gegevens worden opgelsagen 

#define ONE_WIRE_BUS A2    // Temperatuur pin
#define TEMPERATURE_PRECISION 12   //Precisie van de temperatuur meting: 9 rond het af achter de komma, 12 geeft het preciese getal weer  

OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);
DeviceAddress tempDeviceAddress; //↑ Variabelen voor de temperatuur meting 

int numberOfDevices; 
//↑Check van de temperatuur sensor, deze kan met meerdere sensoren werken  

int moistPin = 3;     
int moistReading; 
//↑Vochtigheid pin, en de variable voor de vochtigheidmeting   
 
int photocellPin = 0;     
int photocellReading;
//↑Licht pin, en de variable voor de lichtmeting   
  
double tempReading;
//↑Variabele voor de temperatuurmeting  


void setup() {
  sensors.begin();
  numberOfDevices = sensors.getDeviceCount();

WiFly.begin();
  
  if (!WiFly.join(ssid, passphrase)) {
    Serial.println("Ssid of wachtwoord verkeerd.");
    while (1) {
      // Hang on failure.
      softwareReset();
    }
  } else {
    Serial.println("Connected");
  }
   Serial.println("Setup"); 
 }

double printTemperature(DeviceAddress deviceAddress)
{
  float tempC = sensors.getTempC(deviceAddress);
  //  Serial.print("Temp C: ");
  //Serial.println(tempC);
  return (double) tempC;
}

void softwareReset(){ 
  asm volatile (" jmp 0");  
}



void loop() {
    
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
  } else {
        softwareReset();
  }
  
delay(883000);
  
  
  
  
}
