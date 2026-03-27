<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleUsage extends Model
{
    protected $fillable = [
        'booking_id',
        'start_km',
        'end_km',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function distance()
    {
        return $this->end_km - $this->start_km;
    }
}
