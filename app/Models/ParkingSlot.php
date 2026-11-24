<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ParkingSlot extends Model
{
    protected $fillable = ['floor', 'zone', 'number', 'code', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'sensor_occupied' => 'boolean',
        'sensor_updated_at' => 'datetime',
        'last_plate_detected_at' => 'datetime',
    ];

    public function records(): HasMany
    {
        return $this->hasMany(ParkingRecord::class);
    }

    /** The vehicle parked here right now (released_at is null), if any. */
    public function currentRecord(): HasOne
    {
        return $this->hasOne(ParkingRecord::class)->whereNull('released_at');
    }
}
