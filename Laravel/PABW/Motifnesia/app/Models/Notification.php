<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'link',
        'priority',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get icon based on type
     */
    public function getIconAttribute()
    {
        return match($this->type) {
            'order' => '🛒',
            'stock' => '📦',
            'review' => '⭐',
            'system' => '⚙️',
            default => '🔔'
        };
    }

    /**
     * Get priority color
     */
    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'urgent' => 'red',
            'important' => 'orange',
            'info' => 'blue',
            'normal' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Get relative time
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
