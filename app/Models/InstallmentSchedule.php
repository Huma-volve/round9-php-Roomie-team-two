<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentSchedule extends Model
{
    protected $fillable = [
        'payment_id',
        'installment_number',
        'amount',
        'due_date',
        'status',
        'paid_at',
        'stripe_charge_id',
        'reminder_sent',
        'reminder_sent_at'
    ];
    protected $casts = [
        'due_date' => 'datetime',
        'paid_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }
    public function isOverdue(): bool
    {
        return $this->status === 'pending' && now()->isAfter($this->due_date);
    }
}
