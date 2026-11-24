<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vehicle extends Model
{
    public const FUEL_TYPES = ['petrol', 'diesel', 'octane', 'cng', 'lpg', 'electric', 'hybrid'];
    public const STATUSES = ['active', 'in_maintenance', 'inactive', 'sold'];

    protected $fillable = [
        'vehicle_type_id', 'driver_id', 'registration_no', 'brand', 'model', 'year',
        'color', 'chassis_no', 'engine_no', 'fuel_type', 'seat_capacity', 'odometer',
        'purchase_date', 'purchase_price', 'insurance_expiry', 'tax_token_expiry',
        'fitness_expiry', 'status', 'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'insurance_expiry' => 'date',
        'tax_token_expiry' => 'date',
        'fitness_expiry' => 'date',
        'purchase_price' => 'decimal:2',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    public function fuelLogs(): HasMany
    {
        return $this->hasMany(FuelLog::class);
    }

    public function parkingRecords(): HasMany
    {
        return $this->hasMany(ParkingRecord::class);
    }

    /** Where the vehicle is parked right now (released_at is null), if anywhere. */
    public function currentParking(): HasOne
    {
        return $this->hasOne(ParkingRecord::class)->whereNull('released_at');
    }

    /**
     * Keep the vehicle status in step with its maintenance jobs:
     * any job "in progress" => in_maintenance; none left => back to active.
     * Inactive / sold vehicles are never touched.
     */
    public function syncMaintenanceStatus(): void
    {
        if (in_array($this->status, ['inactive', 'sold'], true)) {
            return;
        }

        $busy = $this->maintenances()->where('status', 'in_progress')->exists();

        if ($busy && $this->status !== 'in_maintenance') {
            $this->update(['status' => 'in_maintenance']);
        } elseif (! $busy && $this->status === 'in_maintenance') {
            $this->update(['status' => 'active']);
        }
    }

    /** Raise the odometer reading, never lower it. */
    public function bumpOdometer(?int $reading): void
    {
        if ($reading !== null && $reading > $this->odometer) {
            $this->update(['odometer' => $reading]);
        }
    }
}
