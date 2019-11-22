#include <ESP8266WiFi.h>
void setupAP(char *APssid, char *APpassword)
{
    Serial.println();
    Serial.print("-----Creating Access Point-----");

    WiFi.mode(WIFI_AP);
    WiFi.softAP(APssid, APpassword);

    Serial.println("-----Access Point Created-----");

    return;
}