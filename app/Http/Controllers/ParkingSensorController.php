<?php

namespace App\Http\Controllers;

use App\Models\ParkingRecord;
use App\Models\ParkingSlot;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParkingSensorController extends Controller
{
    public function sensor(Request $request): JsonResponse
    {
        $data = $request->validate([
            'slot_code' => ['required', 'string', 'max:12'],
            'occupied' => ['required', 'boolean'],
        ]);
        $slotCode = strtoupper(trim($data['slot_code']));

        if (! in_array($slotCode, config('parking_sensors.slots', []), true)) {
            return response()->json(['message' => 'This slot is not configured for sensor updates.'], 403);
        }

        $result = DB::transaction(function () use ($slotCode, $data): array {
            $slot = $this->lockedSlot($slotCode);

            if (! $slot) {
                return ['status' => 'slot_missing_or_ambiguous', 'http_status' => 409];
            }

            if (! $slot->is_active) {
                return ['status' => 'slot_out_of_service', 'http_status' => 409];
            }

            $occupied = (bool) $data['occupied'];
            $slot->sensor_occupied = $occupied;
            $slot->sensor_updated_at = now();
            $record = $slot->currentRecord()->lockForUpdate()->first();

            if ($occupied) {
                if (! $record) {
                    $plate = $slot->last_detected_plate;
                    $vehicle = $this->availableVehicleForPlate($plate);
                    $record = $slot->records()->create([
                        'vehicle_id' => $vehicle?->id,
                        'detected_plate' => $plate,
                        'source' => 'sensor',
                        'parked_at' => now(),
                    ]);
                } else {
                    $record->source = 'sensor';
                    $this->attachDetectedVehicle($record, $slot->last_detected_plate);
                    $record->save();
                }

                $slot->save();

                return [
                    'status' => $record->vehicle_id ? 'occupied_identified' : 'occupied_unidentified',
                    'http_status' => 200,
                ];
            }

            if ($record) {
                $record->update(['released_at' => now()]);
            }

            $slot->last_detected_plate = null;
            $slot->last_plate_detected_at = null;
            $slot->save();

            return ['status' => 'free', 'http_status' => 200];
        });

        return response()->json([
            'slot_code' => $slotCode,
            'status' => $result['status'],
        ], $result['http_status']);
    }

    public function plate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'slot_code' => ['required', 'string', 'max:12'],
            'plate' => ['required', 'string', 'max:50'],
        ]);
        $slotCode = strtoupper(trim($data['slot_code']));
        $plate = strtoupper(trim($data['plate']));

        if (! in_array($slotCode, config('parking_sensors.slots', []), true)) {
            return response()->json(['message' => 'This slot is not configured for sensor updates.'], 403);
        }

        $result = DB::transaction(function () use ($slotCode, $plate): array {
            $slot = $this->lockedSlot($slotCode);

            if (! $slot) {
                return ['status' => 'slot_missing_or_ambiguous', 'http_status' => 409];
            }

            if (! $slot->is_active) {
                return ['status' => 'slot_out_of_service', 'http_status' => 409];
            }

            $slot->last_detected_plate = $plate;
            $slot->last_plate_detected_at = now();
            $record = $slot->currentRecord()->lockForUpdate()->first();

            if (! $record || ! $slot->sensor_occupied) {
                $slot->save();

                return ['status' => 'plate_pending_sensor', 'http_status' => 202];
            }

            $record->detected_plate = $plate;
            $vehicle = $this->availableVehicleForPlate($plate, $record);

            if (! $vehicle) {
                $record->vehicle_id = null;
                $record->save();
                $slot->save();

                return ['status' => 'vehicle_unmatched', 'http_status' => 200];
            }

            $record->vehicle_id = $vehicle->id;
            $record->source = 'sensor';
            $record->save();
            $slot->save();

            return ['status' => 'vehicle_identified', 'http_status' => 200];
        });

        return response()->json([
            'slot_code' => $slotCode,
            'plate' => $plate,
            'status' => $result['status'],
        ], $result['http_status']);
    }

    private function availableVehicleForPlate(?string $plate, ?ParkingRecord $currentRecord = null): ?Vehicle
    {
        if (! $plate) {
            return null;
        }

        $normalizedPlate = str_replace([' ', '-'], '', strtoupper($plate));
        $vehicle = Vehicle::whereRaw(
            "UPPER(REPLACE(REPLACE(registration_no, ' ', ''), '-', '')) = ?",
            [$normalizedPlate]
        )->lockForUpdate()->first();

        if (! $vehicle || in_array($vehicle->status, ['sold', 'inactive'], true)) {
            return null;
        }

        $currentParking = $vehicle->currentParking()->first();

        return $currentParking && $currentParking->id !== $currentRecord?->id ? null : $vehicle;
    }

    private function lockedSlot(string $slotCode): ?ParkingSlot
    {
        $slots = ParkingSlot::where('code', $slotCode)
            ->lockForUpdate()
            ->limit(2)
            ->get();

        return $slots->count() === 1 ? $slots->first() : null;
    }

    private function attachDetectedVehicle(ParkingRecord $record, ?string $plate): void
    {
        if (! $plate) {
            return;
        }

        $record->detected_plate = $plate;
        $vehicle = $this->availableVehicleForPlate($plate, $record);

        if ($vehicle) {
            $record->vehicle_id = $vehicle->id;
        } else {
            $record->vehicle_id = null;
        }
    }
}
