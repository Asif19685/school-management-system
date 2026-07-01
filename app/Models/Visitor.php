<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $table = 'visitors';

    protected $fillable = [
        'name',
        'phone',
        'purpose',
        'meeting_with',
        'time_in',
        'time_out',
        'visitor_type',
    ];

    protected $casts = [
        'time_in'  => 'datetime',
        'time_out' => 'datetime',
    ];

    /** Duration in minutes accessor */
    public function getDurationAttribute(): ?int
    {
        if ($this->time_in && $this->time_out) {
            return (int) $this->time_in->diffInMinutes($this->time_out);
        }
        return null;
    }

    /** Scope: Still inside (no time_out) */
    public function scopeInside($query)
    {
        return $query->whereNull('time_out');
    }
}
