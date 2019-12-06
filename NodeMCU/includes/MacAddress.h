#include <ESP8266WiFi.h>

String showMacAddressList()
{
    String connectedMacAddresses;

    unsigned char numberOfClients;
    struct station_info *stat_info;
    struct ip4_addr *IPaddress;
    IPAddress address;

    numberOfClients = wifi_softap_get_station_num();
    stat_info = wifi_softap_get_station_info();

    Serial.print("Total Connected: ");
    Serial.println(numberOfClients);

    int i = 1;

    while (stat_info != NULL)
    {

        IPaddress = &stat_info->ip;
        address = IPaddress->addr;

        // Serial.print("Client #");
        // Serial.print(i);
        // Serial.print("IP address is: ");
        // Serial.print((address));
        // Serial.print(" with MAC adress is: ");

        uint8 mac[6];
        String s;

        mac[0] = stat_info->bssid[0];
        mac[1] = stat_info->bssid[1];
        mac[2] = stat_info->bssid[2];
        mac[3] = stat_info->bssid[3];
        mac[4] = stat_info->bssid[4];
        mac[5] = stat_info->bssid[5];

        s += String(mac[0], HEX);
        s += ":" + String(mac[1], HEX);
        s += ":" + String(mac[2], HEX);
        s += ":" + String(mac[3], HEX);
        s += ":" + String(mac[4], HEX);
        s += ":" + String(mac[5], HEX);

        // Serial.print(s);

        stat_info = STAILQ_NEXT(stat_info, next);
        i++;

        connectedMacAddresses += "\n" + s;
    }

    delay(100);

    return connectedMacAddresses;
}