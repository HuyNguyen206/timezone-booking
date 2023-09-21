<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Booking extends Model
{
    use HasFactory;
    protected $casts = [
        'timestamp' => 'datetime',
        'start' => 'datetime',
    ];

    protected static $unguarded = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scheduledNotifications(): MorphMany
    {
        return $this->morphMany(ScheduledNotification::class, 'notifiable');
    }
}
