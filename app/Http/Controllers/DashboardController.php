<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\FuelLog;
use App\Models\Maintenance;
use App\Models\ParkingSlot;
use App\Models\Vehicle;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $since = now()->subDays(30)->toDateString();
        $soon = now()->addDays(30)->endOfDay();

        $stats = [
            'total' => Vehicle::count(),
            'active' => Vehicle::where('status', 'active')->count(),
            'in_maintenance' => Vehicle::where('status', 'in_maintenance')->count(),
            'drivers' => Driver::where('is_active', true)->count(),
            'slots_total' => ParkingSlot::where('is_active', true)->count(),
            'slots_free' => ParkingSlot::where('is_active', true)->whereDoesntHave('currentRecord')->count(),
            'fuel_cost' => FuelLog::where('fuel_date', '>=', $since)->sum('total_cost'),
            'maintenance_cost' => Maintenance::where('service_date', '>=', $since)->sum('cost'),
        ];

        // Documents / licences that are expired or expire within 30 days.
        $alerts = collect();

        $documents = [
            'insurance_expiry' => 'Insurance',
            'tax_token_expiry' => 'Tax token',
            'fitness_expiry' => 'Fitness certificate',
        ];

        foreach ($documents as $column => $label) {
            Vehicle::whereNotNull($column)
                ->where($column, '<=', $soon)
                ->whereNotIn('status', ['sold', 'inactive'])
                ->get()
                ->each(fn (Vehicle $v) => $alerts->push([
                    'subject' => $v->registration_no,
                    'what' => $label,
                    'date' => $v->{$column},
                ]));
        }

        Driver::where('is_active', true)
            ->where('license_expiry', '<=', $soon)
            ->get()
            ->each(fn (Driver $d) => $alerts->push([
                'subject' => $d->name,
                'what' => 'Driving licence',
                'date' => $d->license_expiry,
            ]));

        $upcomingServices = Maintenance::with('vehicle')
            ->where('status', 'scheduled')
            ->orderBy('service_date')
            ->limit(5)
            ->get();

        $recentFuel = FuelLog::with('vehicle')->latest('fuel_date')->limit(5)->get();

        return view('dashboard', [
            'stats' => $stats,
            'alerts' => $alerts->sortBy('date')->values(),
            'upcomingServices' => $upcomingServices,
            'recentFuel' => $recentFuel,
        ]);
    }
}
