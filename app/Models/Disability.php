<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Disability extends Model
{
    protected $table = 'disabilities';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Get all students with this disability
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'disability_id');
    }

    /**
     * Scope for active disabilities only
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute(): string
    {
        return $this->status
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-danger">Inactive</span>';
    }
}
