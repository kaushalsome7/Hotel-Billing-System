<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'dob',
        'aadhar_number',
        'aadhar_image_path',
        'photo_path',
        'check_in_purpose',
        'check_in_date',
        'number_of_days',
        'room_id',
    ];

    protected $casts = [
        'dob'           => 'date',
        'check_in_date' => 'date',
        'number_of_days'=> 'integer',
    ];

    /** The room this customer is assigned to (optional). */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /** All restaurant orders placed by this customer. */
    public function restaurantOrders()
    {
        return $this->hasMany(RestaurantOrder::class);
    }

    /** All room service usages by this customer. */
    public function serviceUsages()
    {
        return $this->hasMany(ServiceUsage::class);
    }
}
