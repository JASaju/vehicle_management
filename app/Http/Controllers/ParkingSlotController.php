<?php

namespace App\Http\Controllers;

use App\Models\ParkingSlot;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ParkingSlotController extends Controller
{
    public function index(): View
    {
        return view('parking-slots.index');
    }

    public function create(): View
    {
        return view('parking-slots.form', [
            'slot' => new ParkingSlot(['floor' => 'Ground Floor', 'is_active' => true]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        ParkingSlot::create($data + ['is_active' => $request->boolean('is_active')]);

        return redirect()->route('parking-slots.index')->with('success', "Slot {$data['code']} added.");
    }

    public function edit(ParkingSlot $slot): View
    {
        return view('parking-slots.form', ['slot' => $slot]);
    }

    public function update(Request $request, ParkingSlot $slot): RedirectResponse
    {
        $data = $this->validated($request, $slot);
        $active = $request->boolean('is_active');

        if (
            in_array(strtoupper($slot->code), config('parking_sensors.slots', []), true)
            && $data['code'] !== $slot->code
        ) {
            return back()
                ->withInput()
                ->withErrors(['code' => 'Update PARKING_SENSOR_SLOTS and the hardware mapping before renaming a sensor-managed slot.']);
        }

        if (! $active && $slot->currentRecord()->exists()) {
            return back()
                ->withInput()
                ->withErrors(['is_active' => 'A vehicle is parked here. Release it before taking the slot out of service.']);
        }

        $slot->update($data + ['is_active' => $active]);

        return redirect()->route('parking-slots.index')->with('success', "Slot {$slot->code} updated.");
    }

    /** Create A1..A3 (or B1..B2, ...) in one go. Existing slots are skipped. */
    public function bulk(Request $request): RedirectResponse
    {
        $request->merge(['zone' => strtoupper(trim((string) $request->input('zone')))]);

        $data = $request->validate([
            'floor' => ['required', 'string', 'max:50'],
            'zone' => ['required', 'regex:/^[A-Z]{1,3}$/'],
            'from' => ['required', 'integer', 'between:1,999'],
            'to' => ['required', 'integer', 'gte:from', 'between:1,999'],
        ]);

        $created = 0;

        // At most 100 slots per request.
        foreach (range((int) $data['from'], min((int) $data['to'], (int) $data['from'] + 99)) as $number) {
            $slot = ParkingSlot::firstOrCreate(
                ['floor' => $data['floor'], 'code' => $data['zone'].$number],
                ['zone' => $data['zone'], 'number' => $number, 'is_active' => true],
            );

            $created += $slot->wasRecentlyCreated ? 1 : 0;
        }

        return redirect()->route('parking-slots.index')
            ->with('success', $created.' slot(s) created'.($created === 0 ? ' (they already exist).' : '.'));
    }

    /** Builds the code ("A" + 1 = "A1") and checks it is unique on that floor. */
    private function validated(Request $request, ?ParkingSlot $slot = null): array
    {
        $zone = strtoupper(trim((string) $request->input('zone')));

        $request->merge([
            'zone' => $zone,
            'code' => $zone.$request->input('number'),
        ]);

        return $request->validate([
            'floor' => ['required', 'string', 'max:50'],
            'zone' => ['required', 'regex:/^[A-Z]{1,3}$/'],
            'number' => ['required', 'integer', 'between:1,999'],
            'code' => [
                'required',
                Rule::unique('parking_slots', 'code')->where('floor', $request->input('floor'))->ignore($slot),
            ],
        ]);
    }
}
