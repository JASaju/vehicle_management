<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\FuelLog;
use App\Models\Maintenance;
use App\Models\ParkingRecord;
use App\Models\ParkingSlot;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Services\PdfReport;
use App\Support\Ui;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('reports.index', [
            'vehicles' => Vehicle::orderBy('registration_no')->pluck('registration_no', 'id')->all(),
            'types' => VehicleType::orderBy('name')->pluck('name', 'id')->all(),
            'vehicleStatuses' => Ui::options(Vehicle::STATUSES),
            'maintenanceStatuses' => Ui::options(Maintenance::STATUSES),
        ]);
    }

    /** All vehicles, optionally filtered by status / type. */
    public function vehicles(Request $request): Response
    {
        $f = $request->validate([
            'status' => ['nullable', Rule::in(Vehicle::STATUSES)],
            'vehicle_type_id' => ['nullable', 'exists:vehicle_types,id'],
        ]);

        $vehicles = Vehicle::with(['type', 'driver'])
            ->when($f['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($f['vehicle_type_id'] ?? null, fn ($q, $v) => $q->where('vehicle_type_id', $v))
            ->orderBy('registration_no')
            ->get();

        $subtitle = $this->subtitle([
            'Status' => isset($f['status']) ? Str::headline($f['status']) : null,
            'Type' => isset($f['vehicle_type_id']) ? VehicleType::find($f['vehicle_type_id'])?->name : null,
        ], 'All vehicles');

        return PdfReport::make(
            'reports.pdf.vehicles',
            ['title' => 'Vehicle List', 'subtitle' => $subtitle, 'vehicles' => $vehicles],
            'vehicle-list.pdf',
            landscape: true,
            download: $request->boolean('download'),
        );
    }

    /** All drivers, optionally active / inactive only. */
    public function drivers(Request $request): Response
    {
        $f = $request->validate(['active' => ['nullable', Rule::in(['0', '1'])]]);

        $drivers = Driver::withCount('vehicles')
            ->when(isset($f['active']), fn ($q) => $q->where('is_active', (bool) $f['active']))
            ->orderBy('name')
            ->get();

        $subtitle = $this->subtitle([
            'Status' => isset($f['active']) ? ($f['active'] ? 'Active' : 'Inactive') : null,
        ], 'All drivers');

        return PdfReport::make(
            'reports.pdf.drivers',
            ['title' => 'Driver List', 'subtitle' => $subtitle, 'drivers' => $drivers],
            'driver-list.pdf',
            landscape: true,
            download: $request->boolean('download'),
        );
    }

    /** Maintenance records for a date range / vehicle / status, with total cost. */
    public function maintenance(Request $request): Response
    {
        $f = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'vehicle_id' => ['nullable', 'exists:vehicles,id'],
            'status' => ['nullable', Rule::in(Maintenance::STATUSES)],
        ]);

        $records = Maintenance::with('vehicle')
            ->when($f['from'] ?? null, fn ($q, $v) => $q->whereDate('service_date', '>=', $v))
            ->when($f['to'] ?? null, fn ($q, $v) => $q->whereDate('service_date', '<=', $v))
            ->when($f['vehicle_id'] ?? null, fn ($q, $v) => $q->where('vehicle_id', $v))
            ->when($f['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->orderBy('service_date')
            ->get();

        $subtitle = $this->subtitle([
            'Period' => $this->period($f['from'] ?? null, $f['to'] ?? null),
            'Vehicle' => isset($f['vehicle_id']) ? Vehicle::find($f['vehicle_id'])?->registration_no : null,
            'Status' => isset($f['status']) ? Str::headline($f['status']) : null,
        ], 'All records');

        return PdfReport::make(
            'reports.pdf.maintenance',
            [
                'title' => 'Maintenance Report',
                'subtitle' => $subtitle,
                'records' => $records,
                'totalCost' => $records->sum('cost'),
            ],
            'maintenance-report.pdf',
            landscape: true,
            download: $request->boolean('download'),
        );
    }

    /** Fuel entries for a date range / vehicle, with total liters and cost. */
    public function fuel(Request $request): Response
    {
        $f = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'vehicle_id' => ['nullable', 'exists:vehicles,id'],
        ]);

        $logs = FuelLog::with(['vehicle', 'driver'])
            ->when($f['from'] ?? null, fn ($q, $v) => $q->whereDate('fuel_date', '>=', $v))
            ->when($f['to'] ?? null, fn ($q, $v) => $q->whereDate('fuel_date', '<=', $v))
            ->when($f['vehicle_id'] ?? null, fn ($q, $v) => $q->where('vehicle_id', $v))
            ->orderBy('fuel_date')
            ->get();

        $subtitle = $this->subtitle([
            'Period' => $this->period($f['from'] ?? null, $f['to'] ?? null),
            'Vehicle' => isset($f['vehicle_id']) ? Vehicle::find($f['vehicle_id'])?->registration_no : null,
        ], 'All records');

        return PdfReport::make(
            'reports.pdf.fuel',
            [
                'title' => 'Fuel Report',
                'subtitle' => $subtitle,
                'logs' => $logs,
                'totalLiters' => $logs->sum('liters'),
                'totalCost' => $logs->sum('total_cost'),
            ],
            'fuel-report.pdf',
            landscape: true,
            download: $request->boolean('download'),
        );
    }

    /** Expired items plus anything expiring within N days (vehicle documents and driving licences). */
    public function expiry(Request $request): Response
    {
        $f = $request->validate(['days' => ['nullable', 'integer', 'between:0,365']]);
        $days = (int) ($f['days'] ?? 30);

        $today = now()->startOfDay();
        $limit = now()->addDays($days)->endOfDay();
        $rows = collect();

        $documents = [
            'insurance_expiry' => 'Insurance',
            'tax_token_expiry' => 'Tax token',
            'fitness_expiry' => 'Fitness certificate',
        ];

        foreach ($documents as $column => $label) {
            Vehicle::whereNotNull($column)
                ->where($column, '<=', $limit)
                ->whereNotIn('status', ['sold', 'inactive'])
                ->get()
                ->each(fn (Vehicle $v) => $rows->push([
                    'kind' => 'Vehicle',
                    'subject' => $v->registration_no,
                    'document' => $label,
                    'date' => $v->{$column},
                ]));
        }

        Driver::where('is_active', true)
            ->where('license_expiry', '<=', $limit)
            ->get()
            ->each(fn (Driver $d) => $rows->push([
                'kind' => 'Driver',
                'subject' => $d->name,
                'document' => 'Driving licence',
                'date' => $d->license_expiry,
            ]));

        // Positive = days left, negative = days overdue.
        $rows = $rows
            ->map(function (array $row) use ($today) {
                $row['days_left'] = (int) $today->diffInDays($row['date']->copy()->startOfDay(), false);

                return $row;
            })
            ->sortBy('days_left')
            ->values();

        return PdfReport::make(
            'reports.pdf.expiry',
            [
                'title' => 'Expiry Report',
                'subtitle' => 'Expired and expiring within ' . $days . ' days (as of ' . $today->format('d M Y') . ')',
                'rows' => $rows,
            ],
            'expiry-report.pdf',
            download: $request->boolean('download'),
        );
    }

    /** Fuel + maintenance cost per vehicle for a date range. */
    public function costs(Request $request): Response
    {
        $f = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $from = $f['from'] ?? null;
        $to = $f['to'] ?? null;

        $range = fn (string $column) => function ($q) use ($column, $from, $to) {
            $q->when($from, fn ($q) => $q->whereDate($column, '>=', $from))
                ->when($to, fn ($q) => $q->whereDate($column, '<=', $to));
        };

        $vehicles = Vehicle::query()
            ->withSum(['fuelLogs as fuel_liters' => $range('fuel_date')], 'liters')
            ->withSum(['fuelLogs as fuel_cost' => $range('fuel_date')], 'total_cost')
            ->withSum(['maintenances as maintenance_cost' => $range('service_date')], 'cost')
            ->orderBy('registration_no')
            ->get()
            ->each(function (Vehicle $v) {
                $v->fuel_liters = (float) $v->fuel_liters;
                $v->fuel_cost = (float) $v->fuel_cost;
                $v->maintenance_cost = (float) $v->maintenance_cost;
                $v->total_cost = $v->fuel_cost + $v->maintenance_cost;
            });

        return PdfReport::make(
            'reports.pdf.costs',
            [
                'title' => 'Cost Summary by Vehicle',
                'subtitle' => $this->subtitle(['Period' => $this->period($from, $to)], 'All time'),
                'vehicles' => $vehicles,
                'totals' => [
                    'liters' => $vehicles->sum('fuel_liters'),
                    'fuel' => $vehicles->sum('fuel_cost'),
                    'maintenance' => $vehicles->sum('maintenance_cost'),
                    'all' => $vehicles->sum('total_cost'),
                ],
            ],
            'cost-summary.pdf',
            download: $request->boolean('download'),
        );
    }

    /** Who is parked where right now, plus parking history for a date range. */
    public function parking(Request $request): Response
    {
        $f = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $slots = ParkingSlot::with('currentRecord.vehicle.driver')
            ->orderBy('floor')
            ->orderBy('zone')
            ->orderBy('number')
            ->get();

        $history = ParkingRecord::with(['slot', 'vehicle'])
            ->when($f['from'] ?? null, fn ($q, $v) => $q->whereDate('parked_at', '>=', $v))
            ->when($f['to'] ?? null, fn ($q, $v) => $q->whereDate('parked_at', '<=', $v))
            ->orderByDesc('parked_at')
            ->limit(500)
            ->get();

        $occupied = $slots->filter(fn (ParkingSlot $s) => $s->currentRecord)->count();
        $outOfService = $slots->filter(fn (ParkingSlot $s) => ! $s->is_active && ! $s->currentRecord)->count();

        return PdfReport::make(
            'reports.pdf.parking',
            [
                'title' => 'Parking Report',
                'subtitle' => $this->subtitle(
                    ['History period' => $this->period($f['from'] ?? null, $f['to'] ?? null)],
                    'All history'
                ),
                'slots' => $slots,
                'history' => $history,
                'summary' => [
                    'total' => $slots->count(),
                    'occupied' => $occupied,
                    'out_of_service' => $outOfService,
                    'free' => $slots->count() - $occupied - $outOfService,
                ],
            ],
            'parking-report.pdf',
            landscape: true,
            download: $request->boolean('download'),
        );
    }

    /** Full history of one vehicle: details, maintenance and fuel. */
    public function vehicle(Request $request, Vehicle $vehicle): Response
    {
        $vehicle->load(['type', 'driver']);

        $maintenances = $vehicle->maintenances()->orderByDesc('service_date')->get();
        $fuelLogs = $vehicle->fuelLogs()->with('driver')->orderByDesc('fuel_date')->get();

        return PdfReport::make(
            'reports.pdf.vehicle',
            [
                'title' => 'Vehicle History · ' . $vehicle->registration_no,
                'subtitle' => $vehicle->brand . ' ' . $vehicle->model . ' (' . $vehicle->year . ')',
                'vehicle' => $vehicle,
                'maintenances' => $maintenances,
                'fuelLogs' => $fuelLogs,
                'maintenanceTotal' => $maintenances->sum('cost'),
                'fuelTotal' => $fuelLogs->sum('total_cost'),
                'fuelLiters' => $fuelLogs->sum('liters'),
            ],
            'vehicle-' . Str::slug($vehicle->registration_no) . '.pdf',
            download: $request->boolean('download'),
        );
    }

    private function period(?string $from, ?string $to): ?string
    {
        if (! $from && ! $to) {
            return null;
        }

        $fmt = fn (string $d) => Carbon::parse($d)->format('d M Y');

        return ($from ? $fmt($from) : 'beginning') . ' – ' . ($to ? $fmt($to) : 'today');
    }

    /** ['Status' => 'Active', 'Type' => null] => "Status: Active". Falls back to $default when nothing is set. */
    private function subtitle(array $filters, string $default): string
    {
        $parts = collect($filters)
            ->filter()
            ->map(fn ($value, $label) => $label . ': ' . $value);

        return $parts->isEmpty() ? $default : $parts->implode('  ·  ');
    }
}
