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
        'checkin_status',
        'checkout_status',
        'checkin_time',
        'checkout_time',
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
    
    /**
     * Get a human-readable check-in status
     */
    public function getCheckInStatusLabelAttribute()
    {
        return $this->checkin_status == 'checked_in' ? 'Checked In' : 'Not Checked In';
    }
    
    /**
     * Get a human-readable check-out status
     */
    public function getCheckOutStatusLabelAttribute()
    {
        return $this->checkout_status == 'checked_out' ? 'Checked Out' : 'Not Checked Out';
    }
    
    /**
     * Get formatted check-in time
     */
    public function getFormattedCheckinTimeAttribute()
    {
        return $this->checkin_time ? \Carbon\Carbon::parse($this->checkin_time)->format('M d, Y g:i A') : 'N/A';
    }
    
    /**
     * Get formatted check-out time
     */
    public function getFormattedCheckoutTimeAttribute()
    {
        return $this->checkout_time ? \Carbon\Carbon::parse($this->checkout_time)->format('M d, Y g:i A') : 'N/A';
    }
}
