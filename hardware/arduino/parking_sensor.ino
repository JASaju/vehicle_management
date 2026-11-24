#include <SoftwareSerial.h>
#include <stdio.h>
#include <string.h>

// Edit these values to match the ten configured parking slots and local server.
const char WIFI_SSID[] = "YOUR_WIFI_SSID";
const char WIFI_PASSWORD[] = "YOUR_WIFI_PASSWORD";
const char SERVER_HOST[] = "192.168.1.10";
const uint16_t SERVER_PORT = 80;
const char SENSOR_TOKEN[] = "REPLACE_WITH_A_UNIQUE_32_CHARACTER_OR_LONGER_TOKEN";
const char* SLOT_CODES[10] = {
  "A1", "A2", "A3", "B1", "B2", "B3", "C1", "C2", "D1", "D2"
};
const uint8_t SENSOR_PINS[10] = {2, 3, 4, 5, 6, 7, 8, 9, 10, 11};
const unsigned long DEBOUNCE_MS = 500;

// SoftwareSerial constructor order is RX, TX; keep D0/D1 free for USB serial.
SoftwareSerial esp8266(12, 13);

bool stableOccupied[10];
bool candidateOccupied[10];
bool lastReportedOccupied[10];
unsigned long candidateSince[10];
bool wifiReady = false;

bool waitFor(const char* expected, unsigned long timeoutMs, char* response, size_t capacity)
{
  size_t used = 0;
  const unsigned long startedAt = millis();

  if (capacity > 0) {
    response[0] = '\0';
  }

  while (millis() - startedAt < timeoutMs) {
    while (esp8266.available()) {
      const char value = (char) esp8266.read();

      if (used + 1 < capacity) {
        response[used++] = value;
        response[used] = '\0';
      }

      if (strstr(response, expected) != NULL) {
        return true;
      }
    }
  }

  return false;
}

bool sendCommand(const char* command, const char* expected, unsigned long timeoutMs, char* response, size_t capacity)
{
  while (esp8266.available()) {
    esp8266.read();
  }

  esp8266.print(command);
  esp8266.print("\r\n");
  return waitFor(expected, timeoutMs, response, capacity);
}

bool connectWifi()
{
  char response[256];

  if (!sendCommand("AT", "OK", 3000, response, sizeof(response))) {
    return false;
  }

  sendCommand("ATE0", "OK", 3000, response, sizeof(response));
  if (!sendCommand("AT+CWMODE=1", "OK", 3000, response, sizeof(response))) {
    return false;
  }

  char command[160];
  snprintf(command, sizeof(command), "AT+CWJAP=\"%s\",\"%s\"", WIFI_SSID, WIFI_PASSWORD);
  return sendCommand(command, "OK", 20000, response, sizeof(response));
}

bool postSensorState(uint8_t index, bool occupied)
{
  char response[512];
  char body[96];
  char command[96];

  snprintf(body, sizeof(body), "{\"slot_code\":\"%s\",\"occupied\":%s}",
           SLOT_CODES[index], occupied ? "true" : "false");

  if (!sendCommand("AT+CIPMUX=0", "OK", 3000, response, sizeof(response))) {
    return false;
  }

  snprintf(command, sizeof(command), "AT+CIPSTART=\"TCP\",\"%s\",%u", SERVER_HOST, SERVER_PORT);
  if (!sendCommand(command, "CONNECT", 10000, response, sizeof(response))) {
    char closeResponse[64];
    sendCommand("AT+CIPCLOSE", "OK", 1000, closeResponse, sizeof(closeResponse));
    return false;
  }

  char request[384];
  const int requestLength = snprintf(
    request, sizeof(request),
    "POST /api/parking/sensor HTTP/1.1\r\n"
    "Host: %s\r\n"
    "Authorization: Bearer %s\r\n"
    "Content-Type: application/json\r\n"
    "Connection: close\r\n"
    "Content-Length: %u\r\n\r\n"
    "%s",
    SERVER_HOST, SENSOR_TOKEN, (unsigned int) strlen(body), body
  );

  if (requestLength <= 0 || requestLength >= (int) sizeof(request)) {
    return false;
  }

  snprintf(command, sizeof(command), "AT+CIPSEND=%d", requestLength);
  if (!sendCommand(command, ">", 5000, response, sizeof(response))) {
    char closeResponse[64];
    sendCommand("AT+CIPCLOSE", "OK", 1000, closeResponse, sizeof(closeResponse));
    return false;
  }

  esp8266.print(request);
  if (!waitFor("HTTP/1.1 200", 10000, response, sizeof(response))) {
    char closeResponse[64];
    sendCommand("AT+CIPCLOSE", "OK", 1000, closeResponse, sizeof(closeResponse));
    return false;
  }

  char closeResponse[64];
  sendCommand("AT+CIPCLOSE", "OK", 1000, closeResponse, sizeof(closeResponse));
  return true;
}

void setup()
{
  Serial.begin(9600);
  esp8266.begin(9600);

  for (uint8_t index = 0; index < 10; index++) {
    pinMode(SENSOR_PINS[index], INPUT_PULLUP);
    stableOccupied[index] = digitalRead(SENSOR_PINS[index]) == LOW;
    candidateOccupied[index] = stableOccupied[index];
    lastReportedOccupied[index] = !stableOccupied[index];
    candidateSince[index] = millis();
  }
}

void loop()
{
  if (!wifiReady) {
    wifiReady = connectWifi();
    if (!wifiReady) {
      delay(5000);
      return;
    }

    for (uint8_t index = 0; index < 10; index++) {
      if (postSensorState(index, stableOccupied[index])) {
        lastReportedOccupied[index] = stableOccupied[index];
      } else {
        wifiReady = false;
        break;
      }
    }

    delay(50);
    return;
  }

  for (uint8_t index = 0; index < 10; index++) {
    const bool readingOccupied = digitalRead(SENSOR_PINS[index]) == LOW;

    if (readingOccupied != candidateOccupied[index]) {
      candidateOccupied[index] = readingOccupied;
      candidateSince[index] = millis();
    }

    if (candidateOccupied[index] != stableOccupied[index]
        && millis() - candidateSince[index] >= DEBOUNCE_MS) {
      stableOccupied[index] = candidateOccupied[index];
    }

    if (stableOccupied[index] != lastReportedOccupied[index]) {
      if (postSensorState(index, stableOccupied[index])) {
        lastReportedOccupied[index] = stableOccupied[index];
        Serial.print(SLOT_CODES[index]);
        Serial.println(stableOccupied[index] ? " occupied" : " free");
      } else {
        wifiReady = false;
        delay(1500);
        break;
      }
    }
  }

  delay(50);
}
