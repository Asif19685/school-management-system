<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssuedBook extends Model
{
    protected $table = 'issued_books';

    protected $fillable = [
        'book_id',
        'student_id',
        'issue_date',
        'return_date',
        'status',
    ];

    protected $casts = [
        'issue_date'  => 'date',
        'return_date' => 'date',
    ];

    /** Issued book belongs to a library book */
    public function book(): BelongsTo
    {
        return $this->belongsTo(LibraryBook::class, 'book_id');
    }

    /** Issued book belongs to a student */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /** Check if overdue */
    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'issued' && $this->return_date && $this->return_date->isPast();
    }

    /** Scope: Currently issued */
    public function scopeIssued($query)
    {
        return $query->where('status', 'issued');
    }

    /** Scope: Overdue books */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }
}
