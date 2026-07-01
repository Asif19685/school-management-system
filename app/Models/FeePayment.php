<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeePayment extends Model
{
    protected $table = 'fee_payments';

    protected $fillable = [
        'fee_id',
        'paid_amount',
        'payment_date',
        'payment_method',
        'receipt_no',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'paid_amount'  => 'decimal:2',
    ];

    /** Payment belongs to a fee record */
    public function fee(): BelongsTo
    {
        return $this->belongsTo(Fee::class, 'fee_id');
    }
}
