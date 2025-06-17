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
     */
    public function updating(Room $room)
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
        
        // If checkout_status is changing to checked_out, set the cleaning status to "not_cleaned"
        if ($room->isDirty('checkout_status') && 
            $room->checkout_status === 'checked_out') {
            
            $room->cleaning_status = 'not_cleaned';
        }
    }
    
    /**
     * Handle the Room "updated" event.
     *
     * @param  \App\Models\Room  $room
     * @return void
     */
    public function updated(Room $room)
    {
        // Dispatch Laravel events when room status, cleaning status, or checkin/checkout status changes
        $eventsDispatched = [];
        
        if ($room->isDirty('room_status') || $room->wasChanged('room_status')) {
            // Use Laravel's event system instead of Livewire directly
            event('room-status-updated', ['roomId' => $room->id]);
            $eventsDispatched[] = 'room-status-updated';
        }
        
        if ($room->isDirty('cleaning_status') || $room->wasChanged('cleaning_status')) {
            event('cleaning-status-updated', ['roomId' => $room->id]);
            $eventsDispatched[] = 'cleaning-status-updated';
        }
        
        // Track check-in status changes
        if ($room->isDirty('checkin_status') || $room->wasChanged('checkin_status') || 
            $room->isDirty('checkin_time') || $room->wasChanged('checkin_time')) {
            event('checkin-checkout-updated', [
                'roomId' => $room->id, 
                'action' => 'checkin', 
                'status' => $room->checkin_status
            ]);
            $eventsDispatched[] = 'checkin-checkout-updated (checkin)';
        }
        
        // Track check-out status changes
        if ($room->isDirty('checkout_status') || $room->wasChanged('checkout_status') || 
            $room->isDirty('checkout_time') || $room->wasChanged('checkout_time')) {
            event('checkin-checkout-updated', [
                'roomId' => $room->id, 
                'action' => 'checkout', 
                'status' => $room->checkout_status
            ]);
            $eventsDispatched[] = 'checkin-checkout-updated (checkout)';
        }
        
        // Log the events for debugging
        if (!empty($eventsDispatched)) {
            \Illuminate\Support\Facades\Log::info('Room observer dispatched events: ' . implode(', ', $eventsDispatched) . ' for room ' . $room->id);
        }
    }
}
