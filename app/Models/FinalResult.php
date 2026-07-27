<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinalResult extends Model
{
   protected $table = 'final_results';

    protected $fillable = [
        'exam_id',
        'student_id',
        'class_id',
        'grand_total_marks',
        'grand_obtained_marks',
        'percentage',
        'final_grade',
        'final_status',
        'position',
        'remarks',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
