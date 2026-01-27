<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LifestyleTrait extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'traits',
        'early_bird',
        'smoker',
        'pets',
        'work_from_home',
    ];

    protected $casts = [
        'traits' => 'array',
        'early_bird' => 'boolean',
        'smoker' => 'boolean',
        'work_from_home' => 'boolean',
    ];

    /**
     * Get the user that owns the lifestyle trait.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}