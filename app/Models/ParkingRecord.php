<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParkingRecord extends Model
{
    protected $fillable = [
        'parking_slot_id', 'vehicle_id', 'parked_at', 'released_at',
        'detected_plate', 'source',
    ];

    protected $casts = [
        'parked_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    public function slot(): BelongsTo
    {
        return $this->belongsTo(ParkingSlot::class, 'parking_slot_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
