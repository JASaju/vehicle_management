<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DriverController extends Controller
{
    public function index(): View
    {
        return view('drivers.index');
    }

    public function create(): View
    {
        return view('drivers.form', ['driver' => new Driver(['is_active' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());
        $data['is_active'] = $request->boolean('is_active');

        Driver::create($data);

        return redirect()->route('drivers.index')->with('success', 'Driver added.');
    }

    public function edit(Driver $driver): View
    {
        return view('drivers.form', ['driver' => $driver]);
    }

    public function update(Request $request, Driver $driver): RedirectResponse
    {
        $data = $request->validate($this->rules($driver));
        $data['is_active'] = $request->boolean('is_active');

        $driver->update($data);

        return redirect()->route('drivers.index')->with('success', 'Driver updated.');
    }

    private function rules(?Driver $driver = null): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'license_number' => ['required', 'string', 'max:60', Rule::unique('drivers', 'license_number')->ignore($driver)],
            'license_expiry' => ['required', 'date'],
            'address' => ['nullable', 'string', 'max:255'],
        ];
    }
}
