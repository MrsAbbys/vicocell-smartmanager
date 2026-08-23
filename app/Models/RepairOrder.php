<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_code',
        'client_id',
        'technician_id',
        'device_type',
        'brand',
        'model',
        'imei_serial',
        'reported_fault',
        'diagnosis',
        'status',
        'estimated_cost',
        'advance_payment',
        'promised_date',
    ];

    protected function casts(): array
    {
        return [
            'promised_date' => 'date',
            'estimated_cost' => 'decimal:2',
            'advance_payment' => 'decimal:2',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function getBalanceAttribute(): float
    {
        return (float) ($this->estimated_cost - $this->advance_payment);
    }
}
