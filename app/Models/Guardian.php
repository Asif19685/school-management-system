<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guardian extends Model
{
    protected $table = 'guardians';

    protected $fillable = [
        'father_name',
        'mother_name',
        'father_cnic',
        'father_occupation',
        'complete_address',
        'phone',
        'mother_education',
        'family_monthly_income',
        'emergency_contact',
        'complete_address',
        'postal_address',
       
    ];

    /** Guardian has many students */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'guardian_id');
    }
}
