<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamResult extends Model
{
    protected $table = 'exam_results';

    protected $fillable = [
        'exam_id',
        'student_id',
        'subject_id',
        'obtained_marks',
        'grade',
        'remarks',
    ];

    /** Result belongs to an exam */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    /** Result belongs to a student */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /** Result belongs to a subject */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /** Check if result is pass (>= 50%) */
    public function getIsPassAttribute(): bool
    {
        $exam = $this->exam;
        if (!$exam || $exam->total_marks <= 0) return false;
        return ($this->obtained_marks / $exam->total_marks) >= 0.50;
    }
}
