<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

  protected $fillable = [
        'name',
        'description',
        'price',
        'weight', 
        'image',
        'category',
        'category_id',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'weight' => 'integer', 
        'is_available' => 'boolean',
    ];

    /**
     * Get the category this menu belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}