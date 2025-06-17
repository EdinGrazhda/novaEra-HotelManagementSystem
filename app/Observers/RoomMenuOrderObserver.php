<?php

namespace App\Observers;

use App\Models\RoomMenuOrder;

class RoomMenuOrderObserver
{
    /**
     * Handle the RoomMenuOrder "created" event.
     *
     * @param  \App\Models\RoomMenuOrder  $roomMenuOrder
     * @return void
     */
    public function created(RoomMenuOrder $roomMenuOrder)
    {
        // Dispatch event for real-time updates
        event('food-order-updated', [
            'orderId' => $roomMenuOrder->id,
            'status' => $roomMenuOrder->status,
            'action' => 'created'
        ]);
        
        \Illuminate\Support\Facades\Log::info('Food order created: ID ' . $roomMenuOrder->id . ', Status: ' . $roomMenuOrder->status);
    }

    /**
     * Handle the RoomMenuOrder "updated" event.
     *
     * @param  \App\Models\RoomMenuOrder  $roomMenuOrder
     * @return void
     */
    public function updated(RoomMenuOrder $roomMenuOrder)
    {
        // Check if the status has changed
        if ($roomMenuOrder->isDirty('status') || $roomMenuOrder->wasChanged('status')) {
            // Dispatch event for real-time updates
            event('food-order-updated', [
                'orderId' => $roomMenuOrder->id,
                'status' => $roomMenuOrder->status,
                'previousStatus' => $roomMenuOrder->getOriginal('status'),
                'action' => 'updated'
            ]);
            
            \Illuminate\Support\Facades\Log::info('Food order updated: ID ' . $roomMenuOrder->id . 
                ', Status changed from ' . $roomMenuOrder->getOriginal('status') . ' to ' . $roomMenuOrder->status);
        }
    }

    /**
     * Handle the RoomMenuOrder "deleted" event.
     *
     * @param  \App\Models\RoomMenuOrder  $roomMenuOrder
     * @return void
     */
    public function deleted(RoomMenuOrder $roomMenuOrder)
    {
        // Dispatch event for real-time updates
        event('food-order-updated', [
            'orderId' => $roomMenuOrder->id,
            'action' => 'deleted'
        ]);
        
        \Illuminate\Support\Facades\Log::info('Food order deleted: ID ' . $roomMenuOrder->id);
    }
}
