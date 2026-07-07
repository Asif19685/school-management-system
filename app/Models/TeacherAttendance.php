<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherAttendance extends Model
{
    // Koun koun sy columns fill ho sakty hain
    protected $fillable = [
        'teacher_id',
        'date',
        'check_in',
        'check_out',
        'status'
    ];

    // Relationship: Yeh attendance kis teacher ki hai
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
