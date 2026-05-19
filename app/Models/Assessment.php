<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Assessment extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'assessments';

    protected $fillable = [
        'title',
        'description',
        'type',
        'icon',
        'color',
        'estimated_minutes',
        'category',
        'question_count',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function guidanceRules()
    {
        return $this->hasMany(GuidanceRule::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
