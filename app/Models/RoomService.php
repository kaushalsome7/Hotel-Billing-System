<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',   // SRS: only "Service Name" required; future fields can be added
    ];

    public function serviceUsages()
    {
        return $this->hasMany(ServiceUsage::class);
    }
}
