<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LibraryBook extends Model
{
    protected $table = 'library_books';

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'quantity',
    ];

    /** Book has many issue records */
    public function issuedBooks(): HasMany
    {
        return $this->hasMany(IssuedBook::class, 'book_id');
    }

    /** Count of currently issued (not returned) copies */
    public function getIssuedCountAttribute(): int
    {
        return $this->issuedBooks()->where('status', 'issued')->count();
    }

    /** Count of available copies */
    public function getAvailableCountAttribute(): int
    {
        return max(0, $this->quantity - $this->issued_count);
    }
}
