<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class CrisisResource extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'crisis_resources';

    protected $fillable = [
        'name',
        'phone',
        'country',
        'country_code',    // ISO code for flag emoji
        'description',
        'url',
        'available_hours',
        'languages',
        'type',            // helpline, text_line, chat, emergency
        'region',          // optional subregion
    ];

    protected $casts = [
        'languages' => 'array',
    ];
}
