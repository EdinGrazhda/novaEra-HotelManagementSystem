<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomMenuOrder extends Model
{
    protected $fillable = [
        'room_id',
        'menu_id',
        'quantity',
        'status',
        'notes'
    ];
    
    /**
     * Get the room that owns the order.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
    
    /**
     * Get the menu item that is ordered.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Convert status to human-readable format
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'received' => 'Received',
            'in_process' => 'In Process',
            'delivered' => 'Delivered',
            default => 'Unknown'
        };
    }

    /**
     * Get status badge color class
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'received' => 'bg-blue-100 text-blue-800', // Blue for received
            'in_process' => 'bg-yellow-100 text-yellow-800', // Yellow for in process
            'delivered' => 'bg-green-100 text-green-800', // Green for delivered
            default => 'bg-gray-100 text-gray-800' // Gray for unknown
        };
    }
}
