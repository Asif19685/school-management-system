<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    protected $table = 'sections';

    protected $fillable = ['class_id', 'section_name',  'roll_no',];

    /** Section belongs to a class */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /** Section has many students */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'section_id');
    }
}
