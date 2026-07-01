<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $table = 'courses';

    protected $fillable = [
        'course_name',
        'course_code',
        'duration',
        'fee',
        'description',
    ];

    protected $casts = [
        'fee' => 'decimal:2',
    ];

    /** Course has many subjects */
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'course_id');
    }
}
