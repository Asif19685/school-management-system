<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    protected $table = 'classes';

    protected $fillable = ['class_name', 'description'];

    /** One class has many sections */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'class_id');
    }

    /** One class has many students */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    /** One class has many exams */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'class_id');
    }

    /** Promotions where this class is the target */
    public function promotedStudents(): HasMany
    {
        return $this->hasMany(StudentPromotion::class, 'to_class_id');
    }

    /** Promotions where this class is the source */
    public function promotedFromStudents(): HasMany
    {
        return $this->hasMany(StudentPromotion::class, 'from_class_id');
    }
}
