<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentPromotion extends Model
{
    protected $fillable = [
        'student_id',
        'from_class_id',
        'to_class_id',
        'from_section_id',
        'to_section_id',
        'academic_year',
        'promotion_date',
        'status',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'promotion_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships (No foreign key constraints)
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function fromClass()
    {
        return $this->belongsTo(SchoolClass::class, 'from_class_id');
    }

    public function toClass()
    {
        return $this->belongsTo(SchoolClass::class, 'to_class_id');
    }

    public function fromSection()
    {
        return $this->belongsTo(Section::class, 'from_section_id');
    }

    public function toSection()
    {
        return $this->belongsTo(Section::class, 'to_section_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopePromoted($query)
    {
        return $query->where('status', 'Promoted');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'Failed');
    }

    public function scopeByAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }
}
