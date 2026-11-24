<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    public const TYPES = ['oil_change', 'tire', 'brake', 'battery', 'general_service', 'repair', 'other'];
    public const STATUSES = ['scheduled', 'in_progress', 'completed'];

    protected $fillable = [
        'vehicle_id', 'type', 'description', 'service_date', 'next_service_date',
        'odometer', 'cost', 'workshop', 'status',
    ];

    protected $casts = [
        'service_date' => 'date',
        'next_service_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
