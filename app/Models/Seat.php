<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_zone_id',
        'row',
        'number',
        'is_available',
        'virtual_id'
    ];

    public function venueZone()
    {
        return $this->belongsTo(VenueZone::class);
    }

    public function bookingSeats()
    {
        return $this->hasMany(BookingSeat::class);
    }
}