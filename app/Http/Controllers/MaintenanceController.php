<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Vehicle;
use App\Support\Ui;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaintenanceController extends Controller
{
    public function index(): View
    {
        return view('maintenances.index');
    }

    public function create(): View
    {
        return view('maintenances.form', $this->formData(new Maintenance([
            'type' => 'general_service',
            'status' => 'completed',
            'service_date' => now(),
        ])));
    }

    public function store(Request $request): RedirectResponse
    {
        $maintenance = Maintenance::create($request->validate($this->rules()));

        $this->afterSave($maintenance);

        return redirect()->route('maintenances.index')->with('success', 'Maintenance record added.');
    }

    public function edit(Maintenance $maintenance): View
    {
        return view('maintenances.form', $this->formData($maintenance));
    }

    public function update(Request $request, Maintenance $maintenance): RedirectResponse
    {
        $oldVehicleId = $maintenance->vehicle_id;

        $maintenance->update($request->validate($this->rules()));

        // If the job was moved to another vehicle, refresh the old one too.
        if ($oldVehicleId !== $maintenance->vehicle_id) {
            Vehicle::find($oldVehicleId)?->syncMaintenanceStatus();
        }

        $this->afterSave($maintenance);

        return redirect()->route('maintenances.index')->with('success', 'Maintenance record updated.');
    }

    private function afterSave(Maintenance $maintenance): void
    {
        $vehicle = $maintenance->vehicle;

        $vehicle->bumpOdometer($maintenance->odometer);
        $vehicle->syncMaintenanceStatus();
    }

    private function formData(Maintenance $maintenance): array
    {
        return [
            'maintenance' => $maintenance,
            'vehicles' => Vehicle::orderBy('registration_no')->pluck('registration_no', 'id')->all(),
            'types' => Ui::options(Maintenance::TYPES),
            'statuses' => Ui::options(Maintenance::STATUSES),
        ];
    }

    private function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'type' => ['required', Rule::in(Maintenance::TYPES)],
            'description' => ['nullable', 'string', 'max:255'],
            'service_date' => ['required', 'date'],
            'next_service_date' => ['nullable', 'date', 'after_or_equal:service_date'],
            'odometer' => ['nullable', 'integer', 'min:0'],
            'cost' => ['required', 'numeric', 'min:0'],
            'workshop' => ['nullable', 'string', 'max:150'],
            'status' => ['required', Rule::in(Maintenance::STATUSES)],
        ];
    }
}
