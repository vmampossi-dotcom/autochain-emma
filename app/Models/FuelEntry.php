<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelEntry extends Model
{
    protected $fillable = [
        'vehicle_id',
        'user_id',
        'fuel_amount',
        'cost',
        'mileage',
        'performed_at',
        'station_name',
        'proof_hash',
    ];

    protected $casts = [
        'performed_at' => 'date',
        'fuel_amount' => 'decimal:2',
        'cost' => 'decimal:2',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
