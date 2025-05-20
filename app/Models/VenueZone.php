<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueZone extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'event_id',
        'name',
        'price',
        'capacity',
    ];

    /**
     * Получить событие, к которому относится зона.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Получить места в зоне.
     */
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}