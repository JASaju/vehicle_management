<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelLog extends Model
{
    protected $fillable = [
        'vehicle_id', 'driver_id', 'fuel_date', 'liters', 'price_per_liter',
        'total_cost', 'odometer', 'station',
    ];

    protected $casts = [
        'fuel_date' => 'date',
        'liters' => 'decimal:2',
        'price_per_liter' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
}
