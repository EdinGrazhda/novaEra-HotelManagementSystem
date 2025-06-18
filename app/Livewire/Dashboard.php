<?php

namespace App\Livewire;

use App\Models\Room;
use App\Models\RoomMenuOrder;
use Livewire\Component;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;

class Dashboard extends Component
{
    // System properties
    public $lastUpdated;
    
    // Room statistics
    public $totalRooms;
    public $availableRooms;
    public $occupiedRooms;
    public $maintenanceRooms;
    
    // Cleaning statistics
    public $cleanRooms;
    public $notCleanedRooms;
    public $inProgressCleaningRooms;
    
    // Check-in/Check-out statistics are now handled by RealTimeCheckinCheckout component
    // But we still need this for the dashboard summary
    public $todayActivity;
    
    // Menu order statistics are now handled by RealTimeFoodStatus component
    
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
        // Check if user has cleaner role and redirect them to cleaning service
        if (\Illuminate\Support\Facades\Auth::user()->hasRole('cleaner')) {
            return $this->redirect(route('cleaning.index'), navigate: true);
        }

        logger()->info('Dashboard component mounted at ' . now()->toDateTimeString());
        $this->loadDashboardData();
    }
    
    /**
     * Method that will be called by wire:poll on the cleaning status component
     * to update cleaning status data every 10 seconds
     */
    public function poll()
    {
        try {
            logger()->debug('Cleaning status poll triggered at ' . now()->format('H:i:s'));
            
            // Only update cleaning status data to minimize DB queries
            $this->totalRooms = Room::count();
            $this->cleanRooms = Room::where('cleaning_status', 'clean')->count();
            $this->notCleanedRooms = Room::where('cleaning_status', 'not_cleaned')->count();
            $this->inProgressCleaningRooms = Room::where('cleaning_status', 'in_progress')->count();
            
            // Update todayActivity for the dashboard summary
            $checkinCheckoutStats = \App\Services\DashboardService::getCheckinCheckoutStats();
            $this->todayActivity = $checkinCheckoutStats['todayActivity'];
            
            // Update the last updated timestamp
            $this->lastUpdated = now()->format('H:i:s');
            
            // We don't need to dispatch an event here because Livewire will automatically
            // refresh the component when poll() is called
            
            return [
                'cleanRooms' => $this->cleanRooms,
                'notCleanedRooms' => $this->notCleanedRooms,
                'inProgressCleaningRooms' => $this->inProgressCleaningRooms,
                'todayActivity' => $this->todayActivity
            ];
        } catch (\Exception $e) {
            logger()->error("Error polling cleaning status: {$e->getMessage()}");
            return null;
        }
    }
    
    public function loadDashboardData()
    {
        try {
            // Store previous values for change detection
            $previousValues = [
                'cleanRooms' => $this->cleanRooms ?? 0,
                'notCleanedRooms' => $this->notCleanedRooms ?? 0,
                'inProgressCleaningRooms' => $this->inProgressCleaningRooms ?? 0,
                'availableRooms' => $this->availableRooms ?? 0,
                'occupiedRooms' => $this->occupiedRooms ?? 0
            ];
            
            // Log each refresh for debugging (but only if not polling)
            logger()->debug('Dashboard data loading at ' . now()->toDateTimeString());
            
            // Cache prevention - add a timestamp to ensure fresh data
            $timestamp = now()->timestamp;
            
            // Basic room counts
            $this->totalRooms = Room::count();
            $this->availableRooms = Room::where('room_status', 'available')->count();
            $this->occupiedRooms = Room::where('room_status', 'occupied')->count();
            $this->maintenanceRooms = Room::where('room_status', 'maintenance')->count();
            
            // Cleaning status counts
            $this->cleanRooms = Room::where('cleaning_status', 'clean')->count();
            $this->notCleanedRooms = Room::where('cleaning_status', 'not_cleaned')->count();
            $this->inProgressCleaningRooms = Room::where('cleaning_status', 'in_progress')->count();
            
            // Check-in/Check-out statistics are now handled by RealTimeCheckinCheckout component
            // But we still need todayActivity for the dashboard summary
            $checkinCheckoutStats = \App\Services\DashboardService::getCheckinCheckoutStats();
            $this->todayActivity = $checkinCheckoutStats['todayActivity'];
            
            // Menu order statistics are now handled by RealTimeFoodStatus component
            
            // Monthly statistics for check-ins and check-outs (for charts)
            $this->monthlyData = $this->getMonthlyData();
            
            // Detect changes in key metrics
            $changes = [];
            if ($previousValues['cleanRooms'] != $this->cleanRooms) {
                $changes[] = 'cleanRooms';
            }
            if ($previousValues['notCleanedRooms'] != $this->notCleanedRooms) {
                $changes[] = 'notCleanedRooms';
            }
            if ($previousValues['inProgressCleaningRooms'] != $this->inProgressCleaningRooms) {
                $changes[] = 'inProgressCleaningRooms';
            }
            if ($previousValues['availableRooms'] != $this->availableRooms) {
                $changes[] = 'availableRooms';
            }
            if ($previousValues['occupiedRooms'] != $this->occupiedRooms) {
                $changes[] = 'occupiedRooms';
            }
            
            // If changes were detected, log more verbosely
            if (!empty($changes)) {
                logger()->info('Dashboard data changes detected in: ' . implode(', ', $changes));
            }
        } catch (\Exception $e) {
            logger()->error('Error loading dashboard data: ' . $e->getMessage());
            // Make sure we have some default values
            $this->totalRooms = $this->totalRooms ?? 0;
            $this->cleanRooms = $this->cleanRooms ?? 0;
            $this->notCleanedRooms = $this->notCleanedRooms ?? 0;
            $this->inProgressCleaningRooms = $this->inProgressCleaningRooms ?? 0;
        }
        
        try {
            // Room category distribution
            $this->roomsByCategory = $this->getRoomCategoryDistribution();
    
            // Add a unique timestamp to track updates
            $this->lastUpdated = now()->format('H:i:s');
            
            // No event dispatch needed
        } catch (\Exception $e) {
            logger()->error('Error completing dashboard data load: ' . $e->getMessage());
            // Make sure the timestamp is updated even if there's an error
            $this->lastUpdated = now()->format('H:i:s') . ' (partial refresh)';
        }
        
        return $this;
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
        // Log rendering for debugging poll issues
        logger()->debug('Dashboard render called at ' . now()->toDateTimeString());
        
        // Set current time if not already set
        if (empty($this->lastUpdated)) {
            $this->lastUpdated = now()->format('H:i:s');
        }
        
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
        
        // Food service statistics are now handled by RealTimeFoodStatus component
        
        // Always ensure chart data is available
        if (empty($this->monthlyData)) {
            $this->monthlyData = $this->getMonthlyData();
        }
        
        if (empty($this->roomsByCategory)) {
            $this->roomsByCategory = $this->getRoomCategoryDistribution();
        }
        
        // Ensure todayActivity is always available
        if (!isset($this->todayActivity)) {
            $checkinCheckoutStats = \App\Services\DashboardService::getCheckinCheckoutStats();
            $this->todayActivity = $checkinCheckoutStats['todayActivity'];
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
     * This method is called by the refresh button in the UI
     */
    public function refreshDashboard()
    {
        logger()->info('Manual dashboard refresh triggered by user');
        return $this->loadDashboardData();
    }
    
    /**
     * Handle component disconnection to reduce polling/resource usage
     */
    public function disconnected()
    {
        logger()->info('Dashboard component disconnected');
    }
}
