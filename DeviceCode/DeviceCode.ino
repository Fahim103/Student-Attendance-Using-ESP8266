#include <ESP8266WiFi.h>

#include "./includes/APSetup.h"
#include "./includes/MacAddress.h"

// Setup the access point.
// #TODO Make this user-specified.

char *APssid = "NodeMCU";
char *APpassword = "1234567890";

void setup()
{

    // The module we are using communicates at the 9600 baud rate.
    // Change it according to your device.
    Serial.begin(9600);
    delay(1000);
    setupAP(APssid, APpassword);
    Serial.println(WiFi.softAPIP());
}

void loop()
{
    Serial.println(WiFi.softAPIP());

    delay(5000);
    Serial.println();
    String listOfMacs;
    listOfMacs += showMacAddressList();
    Serial.println(listOfMacs);
}
