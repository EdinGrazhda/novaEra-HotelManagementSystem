<?php

namespace App\Observers;

use App\Models\Room;

class RoomObserver
{
    /**
     * Handle the Room "updating" event.
     *
     * @param  \App\Models\Room  $room
     * @return void
     */    public function updating(Room $room)
    {
        // If the room status is changing from occupied to available (checkout),
        // set the cleaning status to "not_cleaned"
        if ($room->isDirty('room_status') && 
            $room->getOriginal('room_status') === 'occupied' && 
            $room->room_status === 'available') {
            
            $room->cleaning_status = 'not_cleaned';
        }
        
        // If the room status is changing to occupied (checkin),
        // always ensure the cleaning status is "clean"
        if ($room->isDirty('room_status') && 
            $room->room_status === 'occupied') {
            
            $room->cleaning_status = 'clean';
        }
        
        // If the room status is changing from maintenance to available,
        // ensure the cleaning status is set to clean
        if ($room->isDirty('room_status') && 
            $room->getOriginal('room_status') === 'maintenance' && 
            $room->room_status === 'available') {
            
            $room->cleaning_status = 'clean';
        }
    }
}
