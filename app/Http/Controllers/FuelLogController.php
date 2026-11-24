<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\FuelLog;
use App\Models\Vehicle;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FuelLogController extends Controller
{
    public function index(): View
    {
        return view('fuel-logs.index');
    }

    public function create(): View
    {
        return view('fuel-logs.form', $this->formData(new FuelLog(['fuel_date' => now()])));
    }

    public function store(Request $request): RedirectResponse
    {
        $fuelLog = FuelLog::create($this->validated($request));

        $fuelLog->vehicle->bumpOdometer($fuelLog->odometer);

        return redirect()->route('fuel-logs.index')->with('success', 'Fuel record added.');
    }

    public function edit(FuelLog $fuelLog): View
    {
        return view('fuel-logs.form', $this->formData($fuelLog));
    }

    public function update(Request $request, FuelLog $fuelLog): RedirectResponse
    {
        $fuelLog->update($this->validated($request));

        $fuelLog->vehicle->bumpOdometer($fuelLog->odometer);

        return redirect()->route('fuel-logs.index')->with('success', 'Fuel record updated.');
    }

    /** total_cost is always calculated on the server: liters x price per liter. */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'driver_id' => ['nullable', 'exists:drivers,id'],
            'fuel_date' => ['required', 'date'],
            'liters' => ['required', 'numeric', 'gt:0'],
            'price_per_liter' => ['required', 'numeric', 'gt:0'],
            'odometer' => ['nullable', 'integer', 'min:0'],
            'station' => ['nullable', 'string', 'max:150'],
        ]);

        $data['total_cost'] = round($data['liters'] * $data['price_per_liter'], 2);

        return $data;
    }

    private function formData(FuelLog $fuelLog): array
    {
        return [
            'fuelLog' => $fuelLog,
            'vehicles' => Vehicle::orderBy('registration_no')->pluck('registration_no', 'id')->all(),
            'drivers' => Driver::orderBy('name')->pluck('name', 'id')->all(),
        ];
    }
}
