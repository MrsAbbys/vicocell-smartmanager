<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTecnico(): bool
    {
        return $this->role === 'tecnico';
    }

    public function isCliente(): bool
    {
        return $this->role === 'cliente';
    }
    public function client(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Client::class);
    }
    public function assignedRepairs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RepairOrder::class, 'technician_id');
    }
}
