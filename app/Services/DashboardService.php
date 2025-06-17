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
}
