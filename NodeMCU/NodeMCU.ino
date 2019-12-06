#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266mDNS.h>
#include <ESP8266WebServer.h>
#include <FS.h>

#include "./includes/APSetup.h"
#include "./includes/MacAddress.h"
#include "./includes/WriteToFile.h"

#include "pages.h"

// Setup the access point.
// #TODO Make this user-specified.

char *APssid = "NodeMCU";
char *APpassword = "1234567890";

ESP8266WebServer server(80);
String getContentType(String filename);
bool handleFileRead(String path);

String macAddresses[40] = {""}; // A list of mac addresses from the station.

void setup()
{

    // The module we are using communicates at the 9600 baud rate.
    // Change it according to your device.
    Serial.begin(9600);
    delay(1000);
    setupAP(APssid, APpassword);
    setupFileServer();
    // Serial.println(WiFi.softAPIP());
    clearMacFile();
}

void loop()
{
    // Serial.println(WiFi.softAPIP());
    delay(1000);
    Serial.println();
    String listOfMacs;
    listOfMacs += showMacAddressList();
    /*
    TODO:
    Serial.print("---BEFORE---");
    Serial.println(listOfMacs);
    String macs = listOfMacs;

    while (macs.indexOf("\n") != -1)
    {
        int delimiterIndex = macs.indexOf("\n");
        String macAddress = macs.substring(0, delimiterIndex);
        Serial.println("Some index: " + delimiterIndex);
        bool exists = false;

        for (String macFromList : macAddresses)
        {
            Serial.println("Comparing" + macFromList + " To: " + macAddress);
            if (macAddress == macFromList)
            {
                Serial.print("MAC Already Exists");
                exists = true;
                break;
            }
        }

        if (!exists)
        {
            int i = 0;
            for (String macInList : macAddresses)
            {
                if (macInList == "")
                {
                    Serial.println("Writing a MAC");
                    macInList = macAddress;
                    writeMacsToFile(macAddress);
                    break;
                }
            }
        }

        macs.remove(0, delimiterIndex - 1);

    }
    Serial.print("---AFTER---");
    Serial.println(macs);
    */
    writeMacsToFile(listOfMacs);
    server.handleClient();
    // TODO: CREATE TURN OFF CONDITION.
}


/**
 * 
 * All server-side file handling code was taken from the source below:
 * https://shepherdingelectrons.blogspot.com/2019/04/esp8266-as-spiffs-http-server.html
 * 
 */

void setupFileServer()
{
    SPIFFS.begin();

    
    server.on("/",handleRoot);
    server.on("/login", HTTP_POST, handleLogin);
    server.onNotFound([]() {                                  // If the client requests any URI
        if (!handleFileRead(server.uri()))                    // send it if it exists
            server.send(404, "text/plain", "404: Not Found"); // otherwise, respond with a 404 (Not Found) error
    });

    server.begin();
    Serial.println("HTTP server started");
}


String getContentType(String filename)
{
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

void handleWebsite(){
  server.send(200,"text/html", configure);
}

void handleRoot() {                          // When URI / is requested, make login Webpage
  server.send(200, "text/html", login);
}

void handleLogin() {                         //Handle POST Request
  if( ! server.hasArg("username") || ! server.hasArg("password") 
      || server.arg("username") == NULL || server.arg("password") == NULL) { // Request without data
    server.send(400, "text/plain", "400: Invalid Request");         // Print Data on screen
    return;
  }
  if(server.arg("username") == "admin" && server.arg("password") == "admin") { // If username and the password are correct
    server.send(200, "text/html", "<h1>Hello, " + server.arg("username") + "!</h1><p>Login successful</p>");
  } else {                                                                              // Username and password don't match
    server.send(401, "text/plain", "401: Invalid Credentials");
  }
}
