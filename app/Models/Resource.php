<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Resource extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'resources';

    protected $fillable = [
        'title',
        'description',
        'category',
        'content_type',
        'url',
        'tags',
        'icon',
        'body',          // Full content/guide
        'difficulty',    // beginner, intermediate, advanced
        'duration',      // e.g. "5 minutes"
    ];

    protected $casts = [
        'tags' => 'array',
    ];
}
