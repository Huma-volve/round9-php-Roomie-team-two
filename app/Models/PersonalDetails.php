<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalDetails extends Model
{
    protected $table = 'personal_details';

    protected $fillable = [
        'payment_id',
        'user_id',
        'address_line',
        'city',
        'state',
        'postal_code'
    ];
}
