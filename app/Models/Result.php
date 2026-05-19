<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Result extends Model
{
    protected $collection = 'results';

    protected $fillable = [
        'user_id',
        'assessment_id',
        'total_score',
        'answers', // Array of question answers
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
}
