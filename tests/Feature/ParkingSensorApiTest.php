<?php

namespace Tests\Feature;

use Tests\TestCase;

class ParkingSensorApiTest extends TestCase
{
    private const SLOT_CODES = ['A1', 'A2', 'A3', 'B1', 'B2', 'B3', 'C1', 'C2', 'D1', 'D2'];

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'parking_sensors.token' => str_repeat('s', 40),
            'parking_sensors.ocr_token' => str_repeat('o', 40),
            'parking_sensors.slots' => self::SLOT_CODES,
        ]);
    }

    public function test_sensor_endpoint_requires_its_bearer_token(): void
    {
        $this->postJson('/api/parking/sensor', ['slot_code' => 'A1', 'occupied' => true])
            ->assertUnauthorized();
    }

    public function test_ocr_endpoint_requires_its_own_bearer_token(): void
    {
        $this->withToken(str_repeat('s', 40))
            ->postJson('/api/parking/plate', ['slot_code' => 'A1', 'plate' => 'ABC-123'])
            ->assertUnauthorized();
    }

    public function test_sensor_integration_stays_disabled_without_both_tokens_and_ten_slot_codes(): void
    {
        config(['parking_sensors.slots' => ['A1']]);

        $this->withToken(str_repeat('s', 40))
            ->postJson('/api/parking/sensor', ['slot_code' => 'A1', 'occupied' => true])
            ->assertServiceUnavailable();
    }

    public function test_unconfigured_slot_is_rejected_before_accessing_parking_data(): void
    {
        $this->withToken(str_repeat('s', 40))
            ->postJson('/api/parking/sensor', ['slot_code' => 'Z9', 'occupied' => true])
            ->assertForbidden();

        $this->withToken(str_repeat('o', 40))
            ->postJson('/api/parking/plate', ['slot_code' => 'Z9', 'plate' => 'ABC-123'])
            ->assertForbidden();
    }

    public function test_invalid_sensor_payload_is_rejected(): void
    {
        $this->withToken(str_repeat('s', 40))
            ->postJson('/api/parking/sensor', ['slot_code' => 'A1', 'occupied' => 'sometimes'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['occupied']);
    }
}
