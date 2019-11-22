#include <ESP8266WiFi.h>
void setupAP(char *APssid, char *APpassword)
{
    // Maximum of 4 connections allowed.
    Serial.println();
    Serial.print("-----Creating Access Point-----");

    IPAddress local_IP(192,168,135,135);
    IPAddress gateway(192,168,4,9);
    IPAddress subnet(255,255,255,0);

    WiFi.mode(WIFI_AP);
    WiFi.softAPConfig(local_IP, gateway, subnet);
    WiFi.softAP(APssid, APpassword, 1, false, 8);

    Serial.println("-----Access Point Created-----");
    return;
}