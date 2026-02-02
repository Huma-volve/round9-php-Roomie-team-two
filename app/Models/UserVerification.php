<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_verified',
        'phone_verified',
        'id_verified',
        'id_document_path',
        'id_type',
        'rejection_reason',
    ];

    protected $casts = [
        'email_verified' => 'boolean',
        'phone_verified' => 'boolean',
        'id_verified' => 'boolean',
    ];

    protected $hidden = [
        'id_document_path', // Don't expose file path in API
    ];

    /**
     * Get the user that owns the verification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}