<?php

$slotCodes = array_map('trim', explode(',', (string) env('PARKING_SENSOR_SLOTS', '')));
$slotCodes = array_filter($slotCodes, static fn (string $code): bool => $code !== '');

return [
    'token' => env('PARKING_SENSOR_TOKEN'),
    'ocr_token' => env('PARKING_OCR_TOKEN'),
    'slots' => array_values(array_unique(array_map('strtoupper', $slotCodes))),
];
