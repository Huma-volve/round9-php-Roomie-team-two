<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'admin_id',
        'title',
        'description',
        'rent_type',
        'price_per_month',
        'num_rooms',
        'num_bathrooms',
        'max_guests',
        'gender_preference',
        'furnishing',
        'stay_minimum_in_days',
        'deposit',
        'unit_amenities',
        'lifestyle',
        'status',
        'latitude',
        'longitude',
        'available_from'
    ];

    // Cast the unit_amenities and lifestyle fields from json to arrays
    protected $casts = [
        'unit_amenities' => 'array',
        'lifestyle' => 'array',
        'available_from' => 'date',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function mainImage()
    {
        return $this->hasOne(PropertyImage::class)->where('is_main', true);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
