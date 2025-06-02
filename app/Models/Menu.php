<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $table = 'menu';

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the rooms that have ordered this menu item
     */
    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_menu_orders')
                    ->withPivot('quantity', 'status', 'notes')
                    ->withTimestamps();
    }
    
    /**
     * Get all orders for this menu item
     */
    public function orders(): HasMany
    {
        return $this->hasMany(RoomMenuOrder::class);
    }
}
