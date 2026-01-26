<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = [
        'booking_id',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
    ];
}
