<?php

namespace App\Providers;

use App\Models\Room;
use App\Observers\RoomObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the Room observer to handle automatic cleaning status changes
        Room::observe(RoomObserver::class);
        
        // Global event listener for room updates affecting cleaning status
        // This ensures that any updates to rooms from anywhere in the app will
        // properly trigger the dashboard to update
        \Illuminate\Support\Facades\Event::listen(
            'eloquent.updated: ' . Room::class,
            function (Room $room) {
                // Instead of using Livewire events, we'll emit browser events
                // These will be picked up by the Alpine.js listeners in the dashboard
                $events = [];
                
                if ($room->wasChanged('cleaning_status')) {
                    // Fire Laravel events - these will be handled by Livewire listeners
                    event('cleaning-status-updated', ['roomId' => $room->id]);
                    $events[] = 'cleaning-status-updated';
                }
                
                if ($room->wasChanged('room_status')) {
                    event('room-status-updated', ['roomId' => $room->id]);
                    $events[] = 'room-status-updated';
                }
                
                if (!empty($events)) {
                    \Illuminate\Support\Facades\Log::info('Global events dispatched: ' . implode(', ', $events) . ' for room ' . $room->id);
                }
            }
        );
    }
}
