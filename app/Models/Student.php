<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    protected $table = 'students';

    protected $fillable = [
        'registration_no',
        'first_name',
        'last_name',
        'gender',
        'dob',
        'religion',
        'b_form_no',
        'disability_id',
        'additional_disability',
        'disability_certificate_no',
        'previous_school_details',
        'previous_class',
        'cause_of_leaving_school',
        'guardian_id',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function guardian(): BelongsTo
    {
        return $this->belongsTo(Guardian::class, 'guardian_id');
    }

    public function disability(): BelongsTo
    {
        return $this->belongsTo(Disability::class, 'disability_id');
    }

    // Admission relationship
    public function admission(): HasOne
    {
        return $this->hasOne(StudentAdmission::class, 'student_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class, 'student_id');
    }

    public function examResults(): HasMany
    {
        return $this->hasMany(ExamResult::class, 'student_id');
    }

    public function issuedBooks(): HasMany
    {
        return $this->hasMany(IssuedBook::class, 'student_id');
    }

    public function studentImage(): HasOne
    {
        return $this->hasOne(StudentImage::class, 'student_id');
    }
}
