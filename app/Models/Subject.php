<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $fillable = ['course_id', 'subject_name', 'subject_code'];

    /** Subject belongs to a course */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /** Subject has many exam results */
    public function examResults(): HasMany
    {
        return $this->hasMany(ExamResult::class, 'subject_id');
    }
}
