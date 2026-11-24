<?php

namespace App\Http\Controllers;

use App\Models\ParkingRecord;
use App\Models\ParkingSlot;
use App\Models\Vehicle;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ParkingController extends Controller
{
    /** The map: every floor, row by row (A1 A2 A3 / B1 B2 ...). */
    public function index(): View
    {
        $slots = ParkingSlot::with('currentRecord.vehicle.driver')
            ->orderBy('floor')
            ->orderBy('zone')
            ->orderBy('number')
            ->get();

        $occupied = $slots->filter(fn (ParkingSlot $s) => $s->currentRecord)->count();
        $inactive = $slots->filter(fn (ParkingSlot $s) => ! $s->is_active && ! $s->currentRecord)->count();

        // Vehicles that can be parked: not sold / inactive and not already in a slot.
        $available = Vehicle::whereNotIn('status', ['sold', 'inactive'])
            ->whereDoesntHave('currentParking')
            ->orderBy('registration_no')
            ->get(['id', 'registration_no', 'brand', 'model'])
            ->mapWithKeys(fn (Vehicle $v) => [$v->id => $v->registration_no.' · '.$v->brand.' '.$v->model])
            ->all();

        return view('parking.index', [
            'floors' => $slots->groupBy('floor')->map(fn ($floorSlots) => $floorSlots->groupBy('zone')),
            'available' => $available,
            'stats' => [
                'total' => $slots->count(),
                'occupied' => $occupied,
                'inactive' => $inactive,
                'free' => $slots->count() - $occupied - $inactive,
            ],
        ]);
    }

    public function history(): View
    {
        return view('parking.history');
    }

    public function park(Request $request, ParkingSlot $slot): RedirectResponse
    {
        if ($this->isSensorManaged($slot)) {
            return back()->with('error', "Slot {$slot->code} is sensor-managed. Vehicle occupancy is updated automatically.");
        }

        $data = $request->validate(['vehicle_id' => ['required', 'exists:vehicles,id']]);

        try {
            DB::transaction(function () use ($slot, $data) {
                // Lock both rows so two people clicking at once cannot double-book a slot or a vehicle.
                $lockedSlot = ParkingSlot::whereKey($slot->id)->lockForUpdate()->firstOrFail();
                $vehicle = Vehicle::whereKey($data['vehicle_id'])->lockForUpdate()->firstOrFail();

                if (! $lockedSlot->is_active) {
                    throw new RuntimeException("Slot {$lockedSlot->code} is out of service.");
                }

                if ($lockedSlot->currentRecord()->exists()) {
                    throw new RuntimeException("Slot {$lockedSlot->code} is already occupied.");
                }

                if (in_array($vehicle->status, ['sold', 'inactive'], true)) {
                    throw new RuntimeException("{$vehicle->registration_no} is {$vehicle->status} and cannot be parked.");
                }

                $current = $vehicle->currentParking()->with('slot')->first();

                if ($current) {
                    throw new RuntimeException("{$vehicle->registration_no} is already parked in {$current->slot->code}.");
                }

                ParkingRecord::create([
                    'parking_slot_id' => $lockedSlot->id,
                    'vehicle_id' => $vehicle->id,
                    'parked_at' => now(),
                ]);
            });
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        $vehicle = Vehicle::find($data['vehicle_id']);

        return back()->with('success', "{$vehicle->registration_no} parked in {$slot->code}.");
    }

    public function release(ParkingSlot $slot): RedirectResponse
    {
        if ($this->isSensorManaged($slot)) {
            return back()->with('error', "Slot {$slot->code} is sensor-managed. It will be released when its sensor reports that it is free.");
        }

        $record = DB::transaction(function () use ($slot) {
            $record = ParkingRecord::where('parking_slot_id', $slot->id)
                ->whereNull('released_at')
                ->lockForUpdate()
                ->first();

            $record?->update(['released_at' => now()]);

            return $record;
        });

        if (! $record) {
            return back()->with('error', "Slot {$slot->code} is already free.");
        }

        return back()->with('success', ($record->vehicle?->registration_no ?? $record->detected_plate ?? 'Unknown vehicle')." released from {$slot->code}.");
    }

    public function identify(Request $request, ParkingSlot $slot): RedirectResponse
    {
        $data = $request->validate(['vehicle_id' => ['required', 'exists:vehicles,id']]);

        $result = DB::transaction(function () use ($slot, $data): string {
            $lockedSlot = ParkingSlot::whereKey($slot->id)->lockForUpdate()->firstOrFail();
            $record = $lockedSlot->currentRecord()->lockForUpdate()->first();

            if (! $record || $record->vehicle_id) {
                return 'not_assignable';
            }

            $vehicle = Vehicle::whereKey($data['vehicle_id'])->lockForUpdate()->firstOrFail();

            if (in_array($vehicle->status, ['sold', 'inactive'], true) || $vehicle->currentParking()->exists()) {
                return 'vehicle_unavailable';
            }

            $record->vehicle_id = $vehicle->id;
            $record->detected_plate = $record->detected_plate ?: $vehicle->registration_no;
            $record->save();

            return 'assigned';
        });

        if ($result === 'vehicle_unavailable') {
            return back()->withErrors(['vehicle_id' => 'This vehicle is inactive or already parked.']);
        }

        if ($result !== 'assigned') {
            return back()->withErrors(['vehicle_id' => 'This slot has no unidentified vehicle to assign.']);
        }

        return back()->with('success', 'Vehicle identified for slot '.$slot->code.'.');
    }

    private function isSensorManaged(ParkingSlot $slot): bool
    {
        return in_array(strtoupper($slot->code), config('parking_sensors.slots', []), true);
    }
}
