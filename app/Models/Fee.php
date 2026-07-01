<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fee extends Model
{
    protected $table = 'fees';

    protected $fillable = [
        'student_id',
        'fee_type',
        'amount',
        'fine_amount',
        'discount_amount',
        'due_date',
        'status',
    ];

    protected $casts = [
        'due_date'        => 'date',
        'amount'          => 'decimal:2',
        'fine_amount'     => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    

    /** Fee belongs to a student */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /** Fee has many payments */
    public function payments(): HasMany
    {
        return $this->hasMany(FeePayment::class, 'fee_id');
    }

    /** Scope: Pending fees */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /** Scope: Paid fees */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
    // Calculate total payable amount
    public function getTotalAmountAttribute(): float
    {
        return ($this->amount + $this->fine_amount) - $this->discount_amount;
    }

    // Get paid amount
    public function getPaidAmountAttribute(): float
    {
        return $this->payments()->sum('paid_amount');
    }

    // Get due amount
    public function getDueAmountAttribute(): float
    {
        return $this->total_amount - $this->paid_amount;
    }
}
