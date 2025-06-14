<?php

namespace App\Livewire;

use App\Models\Room;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class RealTimeRoomStatus extends Component
{
    public $totalRooms;
    public $availableRooms;
    public $occupiedRooms;
    public $maintenanceRooms;
    public $lastUpdated;
    public $pollingActive = true;

    public function mount()
    {
        $this->loadRoomStatusData();
        logger()->info("RealTimeRoomStatus component mounted at " . now()->toDateTimeString());
    }

    /**
     * Listen for room status updates
     */
    #[On('statusUpdated')]
    public function handleStatusUpdate($roomId = null, $action = null, $status = null)
    {
        logger()->info("Room status changed for room ID: {$roomId}, action: {$action}, status: {$status}");
        // Force refresh of the data
        $this->reset(['totalRooms', 'availableRooms', 'occupiedRooms', 'maintenanceRooms', 'lastUpdated']);
        $this->loadRoomStatusData();
    }
    
    // Also listen for cleaning status updates which can affect room status
    #[On('cleaning-status-updated')]
    public function handleCleaningStatusUpdate($roomId = null)
    {
        logger()->info("Cleaning status updated for room ID: {$roomId}");
        $this->loadRoomStatusData();
    }
    
    // Listen for generic refresh events
    #[On('refresh-dashboard')]
    public function handleRefreshRequest()
    {
        logger()->info("Dashboard refresh requested");
        $this->loadRoomStatusData();
    }
      /**
     * Method called by wire:poll in the blade template
     */
    public function poll()
    {
        if ($this->pollingActive) {
            logger()->debug("Polling triggered at " . now()->format('H:i:s'));
            $this->loadRoomStatusData();
        }
    }
    
    public function togglePolling()
    {
        $this->pollingActive = !$this->pollingActive;
        logger()->info("Polling " . ($this->pollingActive ? "enabled" : "disabled"));
    }
    
    public function loadRoomStatusData()
    {
        try {
            // Query without cache using query builder directly to ensure fresh data
            DB::statement('SET SESSION query_cache_type = OFF');
            
            // Get the latest counts directly from the database
            $this->totalRooms = Room::count();
            $this->availableRooms = Room::where('room_status', 'available')->count();
            $this->occupiedRooms = Room::where('room_status', 'occupied')->count();
            $this->maintenanceRooms = Room::where('room_status', 'maintenance')->count();
            
            // Update the timestamp
            $this->lastUpdated = now()->format('H:i:s');
            
            logger()->debug("Real-time room status updated at {$this->lastUpdated} - Available: {$this->availableRooms}, Occupied: {$this->occupiedRooms}, Maintenance: {$this->maintenanceRooms}");
            
            // Emit event to trigger UI updates via JavaScript
            $this->dispatch('roomStatusUpdated', timestamp: $this->lastUpdated);
            
        } catch (\Exception $e) {
            logger()->error("Error updating room status: {$e->getMessage()}");
        }
    }

    public function render()
    {
        return view('livewire.dashboard-components.real-time-room-status');
    }
}
