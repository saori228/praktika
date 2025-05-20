<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'venue_zone_id',
        'row',
        'number',
        'is_available',
    ];

    /**
     * Получить зону, к которой относится место.
     */
    public function venueZone()
    {
        return $this->belongsTo(VenueZone::class);
    }
}