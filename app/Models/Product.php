<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'min_stock',
        'image',
        'is_active',
    ];

    /**
     * Relación: un producto pertenece a una categoría.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function saleDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SaleDetail::class);
    }
}
