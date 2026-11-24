<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Support\Ui;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    public function index(): View
    {
        return view('vehicles.index');
    }

    public function create(): View
    {
        return view('vehicles.form', $this->formData(new Vehicle(['status' => 'active', 'fuel_type' => 'petrol'])));
    }

    public function store(Request $request): RedirectResponse
    {
        Vehicle::create($request->validate($this->rules()));

        return redirect()->route('vehicles.index')->with('success', 'Vehicle added.');
    }

    public function edit(Vehicle $vehicle): View
    {
        return view('vehicles.form', $this->formData($vehicle));
    }

    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $vehicle->update($request->validate($this->rules($vehicle)));

        return redirect()->route('vehicles.index')->with('success', 'Vehicle updated.');
    }

    private function formData(Vehicle $vehicle): array
    {
        return [
            'vehicle' => $vehicle,
            'types' => VehicleType::orderBy('name')->pluck('name', 'id')->all(),
            'drivers' => Driver::orderBy('name')->pluck('name', 'id')->all(),
            'fuelTypes' => Ui::options(Vehicle::FUEL_TYPES),
            'statuses' => Ui::options(Vehicle::STATUSES),
        ];
    }

    private function rules(?Vehicle $vehicle = null): array
    {
        return [
            'vehicle_type_id' => ['required', 'exists:vehicle_types,id'],
            'driver_id' => ['nullable', 'exists:drivers,id'],
            'registration_no' => ['required', 'string', 'max:50', Rule::unique('vehicles', 'registration_no')->ignore($vehicle)],
            'brand' => ['required', 'string', 'max:80'],
            'model' => ['required', 'string', 'max:80'],
            'year' => ['required', 'integer', 'between:1950,' . ((int) date('Y') + 1)],
            'color' => ['nullable', 'string', 'max:40'],
            'chassis_no' => ['nullable', 'string', 'max:80'],
            'engine_no' => ['nullable', 'string', 'max:80'],
            'fuel_type' => ['required', Rule::in(Vehicle::FUEL_TYPES)],
            'seat_capacity' => ['required', 'integer', 'between:1,100'],
            'odometer' => ['required', 'integer', 'min:0'],
            'purchase_date' => ['nullable', 'date'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'insurance_expiry' => ['nullable', 'date'],
            'tax_token_expiry' => ['nullable', 'date'],
            'fitness_expiry' => ['nullable', 'date'],
            'status' => ['required', Rule::in(Vehicle::STATUSES)],
            'notes' => ['nullable', 'string'],
        ];
    }
}
