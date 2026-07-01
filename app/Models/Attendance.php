<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $table = 'attendance';

    protected $fillable = [
        'student_id',
        'attendance_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    /** Attendance belongs to a student */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /** Scope: Present records */
    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    /** Scope: Absent records */
    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }
}
