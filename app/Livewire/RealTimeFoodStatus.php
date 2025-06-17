<?php

namespace App\Livewire;

use App\Models\RoomMenuOrder;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class RealTimeFoodStatus extends Component
{
    public $totalFoodOrders;
    public $receivedOrders;
    public $preparingOrders;
    public $deliveredOrders;
    public $lastUpdated;
    public $fulfillmentRate;
    public $pollingActive = true;

    public function mount()
    {
        $this->loadFoodStatusData();
        logger()->info("RealTimeFoodStatus component mounted at " . now()->toDateTimeString());
    }

    /**
     * Listen for food order status updates
     */
    #[On('food-order-updated')]
    public function handleFoodOrderUpdate($orderId = null, $status = null)
    {
        logger()->info("Food order status changed for order ID: {$orderId}, status: {$status}");
        // Force refresh of the data
        $this->loadFoodStatusData();
    }
    
    // Listen for generic refresh events
    #[On('refresh-dashboard')]
    public function handleRefreshRequest()
    {
        logger()->info("Dashboard refresh requested for food status");
        $this->loadFoodStatusData();
    }

    /**
     * Method called by wire:poll in the blade template
     */
    public function poll()
    {
        if ($this->pollingActive) {
            logger()->debug("Food status polling triggered at " . now()->format('H:i:s'));
            $this->loadFoodStatusData();
        }
    }
    
    public function togglePolling()
    {
        $this->pollingActive = !$this->pollingActive;
        logger()->info("Food status polling " . ($this->pollingActive ? "enabled" : "disabled"));
    }
    
    public function loadFoodStatusData()
    {
        try {
            // Query without cache using query builder directly to ensure fresh data
            DB::statement('SET SESSION query_cache_type = OFF');
            
            // Get the latest counts directly from the database
            $this->totalFoodOrders = RoomMenuOrder::count();
            $this->receivedOrders = RoomMenuOrder::where('status', 'received')->count();
            $this->preparingOrders = RoomMenuOrder::where('status', 'in_process')->count();
            $this->deliveredOrders = RoomMenuOrder::where('status', 'delivered')->count();
            
            // Calculate fulfillment rate
            $this->fulfillmentRate = $this->totalFoodOrders > 0 ? 
                round(($this->deliveredOrders / $this->totalFoodOrders) * 100) : 0;
            
            // Update the timestamp
            $this->lastUpdated = now()->format('H:i:s');
            
            logger()->debug("Real-time food status updated at {$this->lastUpdated} - Total: {$this->totalFoodOrders}, Received: {$this->receivedOrders}, Preparing: {$this->preparingOrders}, Delivered: {$this->deliveredOrders}");
            
            // Emit event to trigger UI updates via JavaScript
            $this->dispatch('foodStatusUpdated', timestamp: $this->lastUpdated);
            
        } catch (\Exception $e) {
            logger()->error("Error updating food status: {$e->getMessage()}");
        }
    }

    public function render()
    {
        return view('livewire.dashboard-components.real-time-food-status');
    }
}
