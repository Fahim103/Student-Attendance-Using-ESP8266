#include <FS.h>

const char *filename = "/macs.txt";

void writeMacsToFile(String macs)
{
    File f = SPIFFS.open(filename, "a");
    if (!f)
    {
        Serial.println("---File Open Failed!---");
    }
    else
    {
        // Write data to file
        Serial.println("--Writing Mac to File.--");
        f.print(macs);
        f.close();
    }
}

void clearMacFile()
{
    File f = SPIFFS.open(filename, "a");
    
    if (!f)
    {
        Serial.println("---File Clearing Failed!---");
    }
    else
    {
        // Write data to file
        Serial.println("---File Cleared!---");
        SPIFFS.remove(filename);
    }
}