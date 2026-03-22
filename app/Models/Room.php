<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'floor',
        'description',
        'capacity',
        'price_per_day',
    ];

    protected $casts = [
        'capacity'      => 'integer',
        'floor'         => 'integer',
        'price_per_day' => 'decimal:2',
    ];

    /** Customers currently assigned to this room. */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
