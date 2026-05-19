<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class GuidanceRule extends Model
{
    protected $collection = 'guidance_rules';

    protected $fillable = [
        'assessment_id',
        'min_score',
        'max_score',
        'interpretation',
        'recommendation',
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
}
