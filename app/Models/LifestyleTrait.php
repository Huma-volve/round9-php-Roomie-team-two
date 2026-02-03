<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LifestyleTrait extends Model
{
    protected $fillable = [
        'user_id',
        'traits',
        'early_bird',
        'smoker',
        'pets',
        'work_from_home'
    ];

    protected $casts = [
        'traits' => 'array', // مهم جداً عشان الـ JSON
        'early_bird' => 'boolean',
        'smoker' => 'boolean',
        'work_from_home' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}