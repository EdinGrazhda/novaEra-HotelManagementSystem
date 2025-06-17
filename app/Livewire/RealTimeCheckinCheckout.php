<?php

namespace App\Livewire;

use App\Models\Room;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class RealTimeCheckinCheckout extends Component
{
    public $checkedInToday;
    public $checkedOutToday;
    public $pendingCheckouts;
    public $lastUpdated;
    public $pollingActive = true;

    public function mount()
    {
        $this->loadCheckinCheckoutData();
        logger()->info("RealTimeCheckinCheckout component mounted at " . now()->toDateTimeString());
    }

    /**
     * Listen for check-in/check-out status updates
     */
    #[On('checkin-checkout-updated')]
    public function handleCheckinCheckoutUpdate($roomId = null, $action = null, $status = null)
    {
        logger()->info("Check-in/check-out status changed for room ID: {$roomId}, action: {$action}, status: {$status}");
        // Force refresh of the data
        $this->loadCheckinCheckoutData();
    }
    
    // Listen for generic refresh events
    #[On('refresh-dashboard')]
    public function handleRefreshRequest()
    {
        logger()->info("Dashboard refresh requested for check-in/check-out");
        $this->loadCheckinCheckoutData();
    }

    /**
     * Method called by wire:poll in the blade template
     */
    public function poll()
    {
        if ($this->pollingActive) {
            logger()->debug("Check-in/check-out polling triggered at " . now()->format('H:i:s'));
            $this->loadCheckinCheckoutData();
        }
    }
    
    public function togglePolling()
    {
        $this->pollingActive = !$this->pollingActive;
        logger()->info("Check-in/check-out polling " . ($this->pollingActive ? "enabled" : "disabled"));
    }
    
    public function loadCheckinCheckoutData()
    {
        try {
            // Query without cache using query builder directly to ensure fresh data
            DB::statement('SET SESSION query_cache_type = OFF');
            
            // Get the latest counts directly from the database
            $this->checkedInToday = Room::whereDate('checkin_time', now()->toDateString())->count();
            $this->checkedOutToday = Room::whereDate('checkout_time', now()->toDateString())->count();
            $this->pendingCheckouts = Room::where('checkin_status', 'checked_in')
                ->where('checkout_status', '!=', 'checked_out')
                ->count();
            
            // Update the timestamp
            $this->lastUpdated = now()->format('H:i:s');
            
            logger()->debug("Real-time check-in/check-out status updated at {$this->lastUpdated} - Check-ins: {$this->checkedInToday}, Check-outs: {$this->checkedOutToday}, Pending: {$this->pendingCheckouts}");
            
            // Emit event to trigger UI updates via JavaScript
            $this->dispatch('checkinCheckoutStatusUpdated', timestamp: $this->lastUpdated);
            
        } catch (\Exception $e) {
            logger()->error("Error updating check-in/check-out status: {$e->getMessage()}");
        }
    }

    public function render()
    {
        return view('livewire.dashboard-components.real-time-checkin-checkout');
    }
}
