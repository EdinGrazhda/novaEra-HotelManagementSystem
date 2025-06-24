<?php

namespace App\Services;

use App\Models\Room;
use App\Models\RoomMenuOrder;

class DashboardService
{
    /**
     * Get check-in/check-out statistics for the dashboard
     *
     * @return array
     */
    public static function getCheckinCheckoutStats()
    {
        $checkedInToday = Room::whereDate('checkin_time', now()->toDateString())->count();
        $checkedOutToday = Room::whereDate('checkout_time', now()->toDateString())->count();
        $pendingCheckouts = Room::where('checkin_status', 'checked_in')
            ->where('checkout_status', '!=', 'checked_out')
            ->count();
            
        return [
            'checkedInToday' => $checkedInToday,
            'checkedOutToday' => $checkedOutToday,
            'pendingCheckouts' => $pendingCheckouts,
            'todayActivity' => $checkedInToday + $checkedOutToday
        ];
    }
    
    /**
     * Get room status statistics for the dashboard
     *
     * @return array
     */
    public static function getRoomStatusStats()
    {
        $totalRooms = Room::count();
        $availableRooms = Room::where('room_status', 'available')->count();
        $occupiedRooms = Room::where('room_status', 'occupied')->count();
        $maintenanceRooms = Room::where('room_status', 'maintenance')->count();
        
        return [
            'totalRooms' => $totalRooms,
            'availableRooms' => $availableRooms,
            'occupiedRooms' => $occupiedRooms,
            'maintenanceRooms' => $maintenanceRooms
        ];
    }
      /**
     * Get cleaning status statistics for the dashboard
     *
     * @return array
     */
    public static function getCleaningStats()
    {
        $totalRooms = Room::count();
        $cleanRooms = Room::where('cleaning_status', 'clean')->count();
        $notCleanedRooms = Room::where('cleaning_status', 'not_cleaned')->count();
        $inProgressCleaningRooms = Room::where('cleaning_status', 'in_progress')->count();
        
        return [
            'totalRooms' => $totalRooms,
            'cleanRooms' => $cleanRooms,
            'notCleanedRooms' => $notCleanedRooms,
            'inProgressCleaningRooms' => $inProgressCleaningRooms
        ];
    }
    
    /**
     * Get food order status statistics for the dashboard
     *
     * @return array
     */
    public static function getFoodOrderStats()
    {
        $totalFoodOrders = RoomMenuOrder::count();
        $receivedOrders = RoomMenuOrder::where('status', 'received')->count();
        $preparingOrders = RoomMenuOrder::where('status', 'in_process')->count();
        $deliveredOrders = RoomMenuOrder::where('status', 'delivered')->count();
        $fulfillmentRate = $totalFoodOrders > 0 ? round(($deliveredOrders / $totalFoodOrders) * 100) : 0;
        
        return [
            'totalFoodOrders' => $totalFoodOrders,
            'receivedOrders' => $receivedOrders,
            'preparingOrders' => $preparingOrders, 
            'deliveredOrders' => $deliveredOrders,
            'fulfillmentRate' => $fulfillmentRate
        ];
    }
    
    /**
     * Get monthly check-in/check-out trends data
     *
     * @return array
     */
    public function getMonthlyCheckinCheckoutTrends()
    {
        $months = [];
        $checkinCounts = [];
        $checkoutCounts = [];
        
        // Get data for the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthName = $monthDate->format('M');
            $monthYear = $monthDate->year;
            
            // Format month label
            $monthLabel = $monthName . ' ' . $monthYear;
            
            // Count check-ins for this month
            $checkins = Room::whereYear('checkin_time', $monthDate->year)
                ->whereMonth('checkin_time', $monthDate->month)
                ->count();
                
            // Count check-outs for this month
            $checkouts = Room::whereYear('checkout_time', $monthDate->year)
                ->whereMonth('checkout_time', $monthDate->month)
                ->count();
                
            // Add to arrays
            $months[] = $monthLabel;
            $checkinCounts[] = $checkins;
            $checkoutCounts[] = $checkouts;
        }
        
        // Prepare result
        $result = [];
        for ($i = 0; $i < count($months); $i++) {
            $result[] = [
                'month' => $months[$i],
                'checkins' => $checkinCounts[$i],
                'checkouts' => $checkoutCounts[$i]
            ];
        }
        
        return $result;
    }
}
