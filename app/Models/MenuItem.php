<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity_type',   // e.g. "Per Plate", "Per Piece"
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function restaurantOrders()
    {
        return $this->hasMany(RestaurantOrder::class);
    }
}
