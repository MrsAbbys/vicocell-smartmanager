<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'ci_nit',
        'phone',
        'email',
        'address',
    ];

    /**
     * Relación: Un cliente puede estar vinculado a una cuenta de usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function sales(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Sale::class);
    }
    public function repairOrders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RepairOrder::class);
    }
}
