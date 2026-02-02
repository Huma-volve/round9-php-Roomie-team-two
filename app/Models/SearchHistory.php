<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    protected $fillable = [
        'user_id',
        'property_type',
        'bhk',
        'min_budget',
        'max_budget',
        'locality',
        'latitude',
        'longitude',
        'radius_km',
        'search_filters',
        'results_count',
    ];

    protected $casts = [
        'min_budget' => 'decimal:2',
        'max_budget' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'search_filters' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get recent searches for a user
     */
    public function scopeRecent($query, $userId, $limit = 10)
    {
        return $query->where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->limit($limit);
    }

    /**
     * Scope to get searches within a date range
     */
    public function scopeWithinDays($query, $days)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
