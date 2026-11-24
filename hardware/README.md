# Parking sensor and plate-recognition integration

This adds automatic occupancy updates for ten configured parking bays. The Arduino Uno reads ten NPN inductive sensors through suitable isolated 5 V digital interfaces; its ESP-01 Wi-Fi module sends changes to Laravel. A separate IP-camera/OCR service posts recognized plates. GPS is not used: it provides location but not network connectivity or vehicle identification.

## Laravel setup

1. Apply `php artisan migrate` to an existing MySQL or PostgreSQL installation. The new migration makes `parking_records.vehicle_id` nullable so sensor-detected, not-yet-identified vehicles can be represented.
2. Create ten parking slots in the app. Add their exact, globally unique slot codes to `.env` as `PARKING_SENSOR_SLOTS=A1,A2,A3,B1,B2,B3,C1,C2,D1,D2`; replace these examples with your actual ten codes. The API rejects missing or duplicate slot codes in the database.
3. Generate two separate random tokens (at least 32 characters) and set `PARKING_SENSOR_TOKEN` and `PARKING_OCR_TOKEN` in `.env`. Never use the example firmware placeholder as a real token. Refresh Laravel's cached configuration after changing production environment values.
4. Configure the firmware's Wi-Fi credentials, server IP/port, sensor token, and `SLOT_CODES` to match the ten Laravel slot codes. Flash `hardware/arduino/parking_sensor.ino`.
5. Configure the OCR service to POST recognized plates using the OCR token.

The sketch expects the ESP-01 AT firmware UART baud rate to be set to **9600** before use. Verify the module responds to `AT` at that speed with a USB-to-serial adapter before connecting it to the Uno.

```http
POST /api/parking/plate
Authorization: Bearer <PARKING_OCR_TOKEN>
Content-Type: application/json

{"slot_code":"A1","plate":"DHAKA METRO-GA-11-2345"}
```

The Arduino sends state changes (and an initial reading after Wi-Fi connects):

```http
POST /api/parking/sensor
Authorization: Bearer <PARKING_SENSOR_TOKEN>
Content-Type: application/json

{"slot_code":"A1","occupied":true}
```

An OCR event arriving before the occupied sensor event is held as a pending plate for that slot. When both events are available, Laravel matches the normalized plate to an eligible vehicle registration. Unknown plates stay occupied and can be assigned manually from the Parking map. Inactive/sold vehicles and vehicles already parked in another slot are not automatically assigned. Only OCR results that meet the camera system's own confidence/quality threshold should be sent.

Sensor-managed slots are read-only to the manual park/release controls. Their map status refreshes periodically. The API accepts only the ten configured slot codes and uses separate bearer tokens for sensors and OCR. Keep tokens out of source control and browser code.

## Wiring and electrical limits

- Firmware pin mapping uses Arduino **D2-D11** for the ten sensor interface outputs and **D12 (RX)/D13 (TX)** for ESP-01 software serial. D0/D1 remain available for the USB hardware serial monitor. `SLOT_CODES[]` maps each sensor input to the corresponding bay.
- `D` numbers are Arduino digital pin labels, not the physical connector's 1-based position. This mapping intentionally avoids D0/D1 because the Uno uses those pins for USB hardware serial.
- The sample expects an isolated, open-collector 5 V interface that pulls the Arduino input LOW when an NPN sensor detects metal (`INPUT_PULLUP`). Confirm the sensor's NO/NC behavior and the interface polarity against the actual sensor/interface datasheets.
- **Never connect a sensor's 12 V/24 V output directly to an Arduino pin.** Use an appropriately rated optocoupler/interface module; have its ratings and wiring checked by a qualified installer. Grounding and isolation depend on the exact sensor module.
- ESP-01 requires a stable regulated 3.3 V supply with adequate peak current. Do not power it from the Uno's 3.3 V pin. Shift the Uno's 5 V TX signal down to 3.3 V before ESP-01 RX; ESP-01 TX connects to Uno RX.
- The example sketch sends plain HTTP and is for an isolated, trusted local network only. Its bearer token is visible to anyone able to observe that network traffic. Do not expose this endpoint or token to the public internet; production needs a TLS-terminating trusted gateway/device design and network firewalling. The sketch has not been tested against physical hardware in this workspace.

This is an integration starter, not a VAPT result. The supplied SQL was not a VAPT report; finding-specific remediation and SIEM integration remain deferred.
