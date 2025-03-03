<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kick extends Model
{
    protected $fillable = [
        'kick_time',
        'description',
        'user_id',
        'is_active',
    ];

    // Tells Eloquent to treat 'kick_time' as a date
    protected $dates = [
        'kick_time',
    ];

    // If you want to cast 'is_active' to a boolean
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship with user, etc.
}
