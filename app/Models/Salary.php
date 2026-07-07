<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salary extends Model
{
    // Koun koun sy columns fill ho sakty hain
    protected $fillable = [
        'teacher_id',
        'month_year',
        'base_salary',
        'total_present',
        'total_absent',
        'total_half_days',
        'deductions',
        'net_salary',
        'status'
    ];

    // Relationship: Yeh salary kis teacher ki hai
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
