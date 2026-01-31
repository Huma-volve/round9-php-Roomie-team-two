<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HousingPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'preferred_location',
     
        'move_in_date',
    ];

    protected $casts = [
        'move_in_date' => 'date',
    ];

    /**
     * Get the user that owns the housing preference.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}