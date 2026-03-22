<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'room_service_id',
        'price_charged',  // price at the time of service
        'times_used',
    ];

    protected $casts = [
        'price_charged' => 'decimal:2',
        'times_used'    => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function roomService()
    {
        return $this->belongsTo(RoomService::class);
    }
}
