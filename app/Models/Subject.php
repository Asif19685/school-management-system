<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\SubjectResult;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $fillable = [
        'class_id',
        'subject_name',
        'subject_code',
        'total_marks',
        'pass_marks',
    ];

    /** Subject belongs to a school class */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /** Subject has many subject results */
    public function subjectResults(): HasMany
    {
        return $this->hasMany(SubjectResult::class, 'subject_id');
    }
}
