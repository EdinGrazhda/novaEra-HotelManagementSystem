<?php

namespace App\Livewire;

use App\Models\Room;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class CleaningStatusSection extends Component
{
    // Listen for events to update the component
    public $refreshInterval = '3s';
    
    // Cleaning statistics
    public $totalRooms;
    public $cleanRooms;
    public $notCleanedRooms;
    public $inProgressCleaningRooms;
    public $lastUpdated;
    
    public function mount()
    {
        $this->loadCleaningStatusData();
    }
    
    /**
     * Listen for cleaning status updates
     */    #[On('cleaning-status-updated')]
    public function handleCleaningStatusUpdate($roomId = null)
    {
        logger()->info("Cleaning section: status updated for room ID: {$roomId}");
        $this->loadCleaningStatusData();
        
        // Force a refresh of the UI
        $this->dispatch('cleaningStatusChanged', timestamp: now()->format('H:i:s'));
    }
    
    /**
     * Listen for refresh dashboard events
     */
    #[On('refresh-dashboard')]
    public function handleRefreshRequest()
    {
        logger()->info("Cleaning section: dashboard refresh requested");
        $this->loadCleaningStatusData();
    }
      /**
     * Poll method that automatically updates every 3 seconds
     * for real-time dashboard updates
     */
    public function poll()
    {
        logger()->debug("Cleaning status section polling triggered at " . now()->format('H:i:s'));
        $this->loadCleaningStatusData();
    }
    
    /**
     * Load the latest cleaning status data from the database
     */    public function loadCleaningStatusData()
    {
        try {
            // Clear any SQL caching to ensure fresh data
            DB::statement('SET SESSION query_cache_type = OFF');
              // Get fresh data directly from the database (with random query param to break cache)
            $cacheBuster = rand(1000, 9999);
            $this->totalRooms = Room::count();
            $this->cleanRooms = Room::where('cleaning_status', 'clean')->count();
            $this->notCleanedRooms = Room::where('cleaning_status', 'not_cleaned')->count();
            $this->inProgressCleaningRooms = Room::where('cleaning_status', 'in_progress')->count();
            
            // Update timestamp
            $this->lastUpdated = now()->format('H:i:s');
            
            logger()->debug("Cleaning status data updated at {$this->lastUpdated} - Clean: {$this->cleanRooms}, Not Cleaned: {$this->notCleanedRooms}, In Progress: {$this->inProgressCleaningRooms}");
            
            // Dispatch event for JavaScript updates with timestamp
            $this->dispatch('cleaningStatusUpdated', timestamp: $this->lastUpdated);
            
        } catch (\Exception $e) {
            logger()->error("Error updating cleaning status data: {$e->getMessage()}");
        }
    }
      public function render()
    {
        return view('livewire.dashboard-components.cleaning-status-section-new');
    }
}
