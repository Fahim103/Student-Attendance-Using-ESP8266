#include <Adafruit_GFX.h>  // Adafruit Graphics Library
#include <MCUFRIEND_kbv.h> // TFT Display Library
#include "qrcode.h"        // The QR Code Library

MCUFRIEND_kbv tft;

const int pixel_size = 8;

// Define the hex code for black to make it easier to use later.
#define BLACK 0x0000

void setup()
{
    Serial.begin(9600);

    // Get controller ID from the display.
    uint16_t ID = tft.readID();

    // Force the controller to be 0x9481 if something else.
    if (ID == 0xD3)
        ID = 0x9481;
    
    tft.begin(ID);

    // Set the rotation of the display.
    // 0 - Portrait.
    // 1 - Landscape.
    tft.setRotation(0);

    // Generate the QR code
    QRCode qrcode;
    uint8_t qrcodeData[qrcode_getBufferSize(3)];
    qrcode_initText(&qrcode, qrcodeData, 3, 0, "WIFI:T:WPA;S:NodeMCU;P:1234567890;;");
    // The above code generates for the default credentials.
    // TODO: Make the credentials user-defined.

    for (uint8_t y = 0; y < qrcode.size; y++)
    {
        for (uint8_t x = 0; x < qrcode.size; x++)
        {
            if (qrcode_getModule(&qrcode, x, y))
            {
                for (int xi = x * pixel_size + 2; xi < x * pixel_size + pixel_size + 2; xi++)
                {
                    for (int yi = y * pixel_size + 2; yi < y * pixel_size + pixel_size + 2; yi++)
                    {
                        tft.writePixel(xi, yi, BLACK);
                    }
                }
            }
        }
    }

    // Print the course name under the QR Code.
    // TODO: Set this from the user end.
    tft.setFont(NULL);
    tft.setCursor(10, 250);
    tft.setTextColor(BLACK);
    tft.setTextSize(2);
    tft.print("Microprocessor");

    // Print the section label under the QR Code.
    // TODO: Set this from the user end.
    tft.setFont(NULL);
    tft.setCursor(10, 270);
    tft.setTextColor(BLACK);
    tft.setTextSize(2);
    tft.print("Section C");
}

void loop()
{
    // Do nothing.
}
