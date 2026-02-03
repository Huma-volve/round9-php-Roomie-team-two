<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomLoan extends Model
{
    use HasFactory;

    protected $table = 'room_loans';

    // الأعمدة اللي مسموح بالـ mass assignment
    protected $fillable = [
        'room_id',
        'loan_amount',
        'interest_rate',
        'loan_years',
        'start_date',
    ];

    // العلاقة مع الغرفة
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

   
}

