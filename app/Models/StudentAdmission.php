<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentAdmission extends Model
{
    protected $table = 'student_admissions';

    protected $fillable = [
        'student_id',
        'school_name_required',
        'applied_class_id',
        'admission_no',
        'class_id',
        'section_id',

        'admission_date',
        'status',
        'remarks',
        'approved_by_officer',
        'approved_by_head',
    ];

    protected $casts = [
        'admission_date' => 'date',
        'status' => 'string',
    ];

    // Sirf relationships - bas itna kaafi hai!
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function appliedClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'applied_class_id');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
     public function fees(): HasMany
    {
        return $this->hasMany(Fee::class, 'student_id', 'student_id');
    }
}
