<?php

namespace App\Livewire;

use App\Models\Room;
use App\Models\RoomMenuOrder;
use Livewire\Component;
use Illuminate\Support\Carbon;

class Dashboard extends Component
{
    // Room statistics
    public $totalRooms;
    public $availableRooms;
    public $occupiedRooms;
    public $maintenanceRooms;
    
    // Cleaning statistics
    public $cleanRooms;
    public $notCleanedRooms;
    public $inProgressCleaningRooms;
    
    // Check-in/Check-out statistics
    public $checkedInToday;
    public $checkedOutToday;
    public $pendingCheckouts;
    
    // Menu order statistics
    public $totalFoodOrders;
    public $receivedOrders;
    public $preparingOrders;
    public $deliveredOrders;
    
    // Chart data
    public $monthlyData;
    public $roomsByCategory;

    /**
     * Get the monthly data for the chart via JavaScript
     */
    public function getChartMonthlyData()
    {
        return $this->monthlyData;
    }
    
    /**
     * Get the room category distribution data for the chart via JavaScript
     */
    public function getChartRoomCategories()
    {
        return $this->roomsByCategory;
    }
    

    public function mount()
    {
        $this->loadDashboardData();
        // Ensure browser event is dispatched to render charts on first load
        $this->dispatch('dashboard-data-updated');
    }
    
    public function loadDashboardData()
    {
        // Basic room counts
        $this->totalRooms = Room::count();
        $this->availableRooms = Room::where('room_status', 'available')->count();
        $this->occupiedRooms = Room::where('room_status', 'occupied')->count();
        $this->maintenanceRooms = Room::where('room_status', 'maintenance')->count();
        
        // Cleaning status counts
        $this->cleanRooms = Room::where('cleaning_status', 'clean')->count();
        $this->notCleanedRooms = Room::where('cleaning_status', 'not_cleaned')->count();
        $this->inProgressCleaningRooms = Room::where('cleaning_status', 'in_progress')->count();
        
        // Check-in/Check-out statistics
        $this->checkedInToday = Room::whereDate('checkin_time', now()->toDateString())->count();
        $this->checkedOutToday = Room::whereDate('checkout_time', now()->toDateString())->count();
        $this->pendingCheckouts = Room::where('checkin_status', 'checked_in')
            ->where('checkout_status', '!=', 'checked_out')
            ->count();
        
        // Menu order statistics
        $this->totalFoodOrders = RoomMenuOrder::count();
        $this->receivedOrders = RoomMenuOrder::where('status', 'received')->count();
        $this->preparingOrders = RoomMenuOrder::where('status', 'in_process')->count();
        $this->deliveredOrders = RoomMenuOrder::where('status', 'delivered')->count();
        
        // Monthly statistics for check-ins and check-outs (for charts)
        $this->monthlyData = $this->getMonthlyData();
        
        // Room category distribution
        $this->roomsByCategory = $this->getRoomCategoryDistribution();

        // Dispatch browser event to refresh charts
        $this->dispatch('dashboard-data-updated');
    }

    private function getMonthlyData()
    {
        $monthlyData = [];
        $currentMonth = now()->startOfMonth();
        
        // Get data for the last 6 months and reverse to show chronologically
        for ($i = 5; $i >= 0; $i--) {
            $month = $currentMonth->copy()->subMonths($i);
            $monthName = $month->format('M Y');
            
            $monthlyCheckins = Room::whereMonth('checkin_time', $month->month)
                ->whereYear('checkin_time', $month->year)
                ->count();
                
            $monthlyCheckouts = Room::whereMonth('checkout_time', $month->month)
                ->whereYear('checkout_time', $month->year)
                ->count();
                
            $monthlyData[] = [
                'month' => $monthName,
                'checkins' => $monthlyCheckins,
                'checkouts' => $monthlyCheckouts
            ];
        }
        
        return $monthlyData;
    }
    
    private function getRoomCategoryDistribution()
    {
        return Room::with('roomCategory')
            ->get()
            ->groupBy('roomCategory.category_name')
            ->map(function($items, $key) {
                return [
                    'category' => $key ?? 'Uncategorized',
                    'count' => $items->count()
                ];
            })
            ->values();
    }

    public function render()
    {
        // Ensure data is loaded in case the mount or loadDashboardData didn't execute correctly
        if (empty($this->totalRooms)) {
            $this->totalRooms = Room::count();
        }
        
        if (empty($this->occupiedRooms)) {
            $this->occupiedRooms = Room::where('room_status', 'occupied')->count();
        }
        
        if (empty($this->cleanRooms)) {
            $this->cleanRooms = Room::where('cleaning_status', 'clean')->count();
        }
        
        // Ensure food service statistics are available
        if (empty($this->totalFoodOrders)) {
            $this->totalFoodOrders = RoomMenuOrder::count();
        }
        
        if (!isset($this->receivedOrders)) {
            $this->receivedOrders = RoomMenuOrder::where('status', 'received')->count();
        }
        
        if (!isset($this->preparingOrders)) {
            $this->preparingOrders = RoomMenuOrder::where('status', 'in_process')->count();
        }
        
        if (empty($this->deliveredOrders)) {
            $this->deliveredOrders = RoomMenuOrder::where('status', 'delivered')->count();
        }
        
        // Always ensure chart data is available
        if (empty($this->monthlyData)) {
            $this->monthlyData = $this->getMonthlyData();
        }
        
        if (empty($this->roomsByCategory)) {
            $this->roomsByCategory = $this->getRoomCategoryDistribution();
        }
        
        // Don't dispatch event in render as it can cause infinite loops with Livewire navigation
        
        return view('livewire.dashboard');
    }
    
    /**
     * Lifecycle hook that fires after the component has been hydrated but before it renders
     * Perfect place to ensure chart data is available when navigating between pages
     */
    public function hydrate()
    {
        // Ensure all data is loaded when component is hydrated after navigation
        if (empty($this->monthlyData) || empty($this->roomsByCategory)) {
            $this->loadDashboardData();
        } else {
            // Just dispatch the event to update charts with existing data
            $this->dispatch('dashboard-data-updated');
        }
    }
    
    /**
     * Method for refreshing dashboard data manually
     */
    public function refreshDashboard()
    {
        $this->loadDashboardData();
    }
}
