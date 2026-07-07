<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends Model
{
    protected $table = 'teachers';

    protected $fillable = [
        'user_id',
        'teacher_code',
        'name',
        'email',
        'phone',
        'qualification',
        'joining_date',
        'salary',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'salary'       => 'decimal:2',
    ];

    /** Teacher optionally linked to a User account */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendances() {
    return $this->hasMany(TeacherAttendance::class);
}
public function salaries() {
    return $this->hasMany(Salary::class);
}
}
