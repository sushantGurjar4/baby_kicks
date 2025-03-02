<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kick extends Model
{
    protected $fillable = [
        'kick_time',
        'description',
    ];

    // If you want kick_time as a Carbon date:
    protected $dates = ['kick_time'];
}
