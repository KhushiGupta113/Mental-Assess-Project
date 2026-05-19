<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Question extends Model
{
    protected $collection = 'questions';

    protected $fillable = [
        'assessment_id',
        'text',
        'order',
        'options', // Array of ['label' => '...', 'score' => 0]
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
}
