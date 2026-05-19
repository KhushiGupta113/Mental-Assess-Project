<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class MoodLog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'mood_logs';

    protected $fillable = [
        'user_id',
        'mood_emoji',
        'mood_label',
        'mood_score',
        'notes',
        'energy_level',
        'sleep_hours',
        'triggers',
        'activities',
    ];

    protected $casts = [
        'triggers' => 'array',
        'activities' => 'array',
        'mood_score' => 'integer',
        'energy_level' => 'integer',
        'sleep_hours' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
