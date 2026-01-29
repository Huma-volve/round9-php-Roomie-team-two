<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'property_id',
        'room_number',
        'room_type',
        'price_per_month',
        'num_beds',
        'room_bed_type',
        'size_in_sq_m',
        'capacity',
        'current_roomates',
        'room_amenities',
        'status',
        'available_from',
        'deposit',
        'minimum_stay'
    ];

    // Cast room_amenities from JSON to array
    protected $casts = [
        'room_amenities' => 'array'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function roomImages()
    {
        return $this->hasMany(RoomImage::class);
    }

    public function loans()
    {
        return $this->hasMany(RoomLoan::class);
    }
}
