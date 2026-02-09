<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'reservation_code',
        'payment_type',
        'amount',
        'amount_paid',
        'payment_method',
        'payment_status',
        'stripe_payment_intent_id',
        'payment_due_date',
        'stripe_response',
        'paid_at',
    ];
    protected $casts = [
        'paid_at' => 'datetime',
        'payment_due_date' => 'datetime',
        'stripe_response' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function personalDetails()
    {
        return $this->hasOne(PersonalDetails::class);
    }

    public function installmentSchedules(): HasMany
    {
        return $this->hasMany(InstallmentSchedule::class);
    }

    public function getAmountRemainingAttribute()
    {
        return $this->amount - $this->amount_paid;
    }
}
