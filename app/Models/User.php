<?php

namespace App\Models;

 use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'job_title',
        'aboutme',
        'max_budget',
        'address',
        'gender',
        'image',
        'email_verified_at',
        'is_verified', // ⭐ إضافة is_verified هنا
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean', // ⭐ إضافة casting للـ is_verified
        ];
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    /**
     * Get the housing preferences for the user.
     */
    public function housingPreferences()
    {
        return $this->hasMany(HousingPreference::class);
    }

    /**
     * Get the verification for the user.
     */
    public function verification()
    {
        return $this->hasOne(UserVerification::class);
    }

    /**
     * Get the lifestyle trait for the user.
     */
    public function lifestyleTrait()
    {
        return $this->hasOne(LifestyleTrait::class);
    }
}