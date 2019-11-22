#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266mDNS.h>
#include <ESP8266WebServer.h>
#include <FS.h>

#include "./includes/APSetup.h"
#include "./includes/MacAddress.h"
#include "./includes/WriteToFile.h"

// Setup the access point.
// #TODO Make this user-specified.

char *APssid = "NodeMCU";
char *APpassword = "1234567890";

ESP8266WebServer server(80);
String getContentType(String filename); // convert the file extension to the MIME type
bool handleFileRead(String path);       // send the right file to the client (if it exists)

void setup()
{

    // The module we are using communicates at the 9600 baud rate.
    // Change it according to your device.
    Serial.begin(9600);
    delay(1000);
    setupAP(APssid, APpassword);
    setupFileServer();
    Serial.println(WiFi.softAPIP());
}

void loop()
{
    Serial.println(WiFi.softAPIP());
    delay(50);
    Serial.println();
    String listOfMacs;
    listOfMacs += showMacAddressList();
    Serial.println(listOfMacs);

    writeMacsToFile(listOfMacs);

    server.handleClient();
}

void setupFileServer()
{
    if (MDNS.begin("esp8266"))
    { // Start the mDNS responder for esp8266.local
        Serial.println("mDNS responder started");
    }
    else
    {
        Serial.println("Error setting up MDNS responder!");
    }

    SPIFFS.begin(); // Start the SPI Flash Files System

    server.onNotFound([]() {                                  // If the client requests any URI
        if (!handleFileRead(server.uri()))                    // send it if it exists
            server.send(404, "text/plain", "404: Not Found"); // otherwise, respond with a 404 (Not Found) error
    });

    server.begin(); // Actually start the server
    Serial.println("HTTP server started");
}

String getContentType(String filename)
{ // convert the file extension to the MIME type
    if (filename.endsWith(".html"))
        return "text/html";
    else if (filename.endsWith(".css"))
        return "text/css";
    else if (filename.endsWith(".js"))
        return "application/javascript";
    else if (filename.endsWith(".ico"))
        return "image/x-icon";
    return "text/plain";
}

bool handleFileRead(String path)
{
    // send the right file to the client (if it exists)
    Serial.println("handleFileRead: " + path);
    if (path.endsWith("/"))
        path += "index.html";                  // If a folder is requested, send the index file
    String contentType = getContentType(path); // Get the MIME type
    if (SPIFFS.exists(path))
    {                                                       // If the file exists
        File file = SPIFFS.open(path, "r");                 // Open it
        size_t sent = server.streamFile(file, contentType); // And send it to the client
        file.close();                                       // Then close the file again
        return true;
    }
    Serial.println("\tFile Not Found");
    return false; // If the file doesn't exist, return false
}