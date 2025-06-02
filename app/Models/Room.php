<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';

    protected $fillable = [
        'room_number',
        'room_floor',
        'room_type',
        'room_status',
        'room_description',
        'room_category_id',
        'cleaning_status',
        'cleaning_notes',
    ];

    public function roomCategory()
    {
        return $this->belongsTo(RoomCategory::class, 'room_category_id');
    }
    
    // Relationship to menu items through RoomMenuOrder
    public function menuOrders()
    {
        return $this->hasMany(RoomMenuOrder::class);
    }
    
    // Direct relationship to Menu (if applicable)
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'room_menu_orders')
                    ->withPivot('quantity', 'status', 'notes')
                    ->withTimestamps();
    }
    
    // This is an alias for the roomCategory relation to make it easier to use in views
    public function category()
    {
        return $this->roomCategory();
    }
}
