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
        'exam_type',
        'academic_year',
        'class_id',
        'exam_date',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'academic_year' => 'integer',
    ];

    /** Exam belongs to a class */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /** Exam has many subject results */
    public function subjectResults(): HasMany
    {
        return $this->hasMany(SubjectResult::class, 'exam_id');
    }

    /** Exam has many final results */
    public function finalResults(): HasMany
    {
        return $this->hasMany(FinalResult::class, 'exam_id');
    }
}
