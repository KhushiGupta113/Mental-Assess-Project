<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // Demographics & personalization
        'age_group',
        'gender',
        'country',
        'occupation',
        'concerns',              // array of primary concerns (anxiety, depression, stress, sleep, etc.)
        'onboarding_completed',  // boolean flag
        // Profile
        'avatar_emoji',
        'lifestyle_habits',
        'sleep_tracking',
        'is_anonymous',
        'wellness_goals',
        'preferences',
        // Gamification
        'current_streak',
        'longest_streak',
        'last_activity_date',
        'total_assessments',
        'total_journal_entries',
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
            'is_anonymous' => 'boolean',
            'onboarding_completed' => 'boolean',
            'wellness_goals' => 'array',
            'preferences' => 'array',
            'concerns' => 'array',
            'current_streak' => 'integer',
            'longest_streak' => 'integer',
            'total_assessments' => 'integer',
            'total_journal_entries' => 'integer',
        ];
    }

    public function moodLogs()
    {
        return $this->hasMany(MoodLog::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }
}
