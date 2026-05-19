<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class JournalEntry extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'journal_entries';

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'mood_tag',
        'sentiment_score',
        'tags',
        'is_gratitude',
        'ai_prompt',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_gratitude' => 'boolean',
        'sentiment_score' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
