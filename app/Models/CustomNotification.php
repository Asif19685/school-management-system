<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Custom Notification model mapped to the 'notifications' table.
 * Named CustomNotification to avoid conflict with Laravel's built-in
 * Illuminate\Notifications\DatabaseNotification class.
 */
class CustomNotification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'channel',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /** Notification belongs to a user */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Mark as read */
    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    /** Scope: Unread notifications */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
