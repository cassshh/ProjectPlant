// Wifly shield library's
#include <SPI.h>    
#include <WiFly.h>  

// Temperatuur sensor library's 
#include <OneWire.h>
#include <DallasTemperature.h>  

// Het bestand waar de WiFly instellingen zijn gedefinieerd. 
// SSID en Wachtwoord van het Wi-fi netwerk waarmee vorbonden moet worden
#include "Credentials.h"  

// De server waarmee de WiFly verinding moet maken
// Hier worden de gemeten gegevens naar verzonden en opgeslagen
WiFlyClient client("casnetwork.tk", 80);  

// Temperatuur sensor pin
#define ONE_WIRE_BUS A2    
// Precisie van de temperatuur meting: 9 rond het af achter de komma, 12 geeft het preciese getal weer  
#define TEMPERATURE_PRECISION 12   

// Variabelen voor de temperatuur meting
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);
DeviceAddress tempDeviceAddress; 

// Check voor de temperatuur sensor, deze kan met meerdere sensoren werken  
int numberOfDevices; 

// Vochtigheid pin, en de variable voor de vochtigheidmeting   
int moistPin = 3;     
int moistReading; 
 
// Licht pin, en de variable voor de lichtmeting  
int photocellPin = 0;     
int photocellReading; 

// Variabele voor de temperatuurmeting  
double tempReading;


// Setup loop als arduino wordt aangezet
void setup() {
  
  // Start sensors
  sensors.begin();
  numberOfDevices = sensors.getDeviceCount();
  
  // Start WiFly shield
  WiFly.begin();

  // Check of het WiFly shield verbinding heeft gemaakt met het netwerk
  if (!WiFly.join(ssid, passphrase)) {
    Serial.println("Ssid of wachtwoord verkeerd.");

    while (1) {
      // Reset arduino en probeer opnieuw verbinding te maken
      softwareReset();
    }
  } else {
    Serial.println("Connected");
  }
  Serial.println("Setup"); 
}

// Functie om de temperatuur af te lezen van de sensor(s)
double printTemperature(DeviceAddress deviceAddress)
{
  float tempC = sensors.getTempC(deviceAddress);
  
  return (double) tempC;
}

// Functie om de arduino te resetten
void softwareReset(){ 
  asm volatile (" jmp 0");  
}

// Main loop
void loop() {
  
  // Haal sensor data op
  moistReading = analogRead(moistPin); 
  photocellReading = analogRead(photocellPin); 
  sensors.requestTemperatures(); 

  // Krijg de temperatuur voor elke temperatuur sensor
  for(int i=0;i<numberOfDevices; i++)
  {
    if(sensors.getAddress(tempDeviceAddress, i))
    {
      tempReading = printTemperature(tempDeviceAddress);
    }
  }
  
  Serial.println();

  // Maak verbinding met de server en verzend de waarden van de sensoren in een GET
  // Het PHP script op de server zorgt ervoor dat deze in de database worden gezet
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
    // Reset de arduino als er geen verbinding kon worden gemaakt met de server
    softwareReset();
  }
  
// Tijd in miliseconden tussen metingen  
delay(883000);  
  
}
