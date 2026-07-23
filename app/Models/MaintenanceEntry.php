<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceEntry extends Model
{
    protected $fillable = [
        'vehicle_id',
        'user_id',
        'title',
        'maintenance_type',
        'repairer_name',
        'mileage',
        'performed_at',
        'description',
        'cost',
        'critical',
        'proof_hash',
    ];

    protected $casts = [
        'performed_at' => 'date',
        'critical' => 'boolean',
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
