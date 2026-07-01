<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $table = 'exams';

    protected $fillable = [
        'exam_name',
        'class_id',
        'exam_date',
        'total_marks',
    ];

    protected $casts = [
        'exam_date' => 'date',
    ];

    /** Exam belongs to a class */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /** Exam has many results */
    public function results(): HasMany
    {
        return $this->hasMany(ExamResult::class, 'exam_id');
    }
}
