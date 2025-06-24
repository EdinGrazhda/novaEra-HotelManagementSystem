<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ChartController extends Controller
{
    /**
     * Get monthly check-in/check-out data for the dashboard chart
     */
    public function getMonthlyData()
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
        
        return response()->json($monthlyData);
    }
    
    /**
     * Get room category distribution data for the dashboard chart
     */
    public function getRoomCategoryDistribution()
    {
        $roomsByCategory = Room::with('roomCategory')
            ->get()
            ->groupBy('roomCategory.category_name')
            ->map(function($items, $key) {
                return [
                    'category' => $key ?? 'Uncategorized',
                    'count' => $items->count()
                ];
            })
            ->values();
            
        return response()->json($roomsByCategory);
    }
    
    /**
     * Get dashboard summary statistics
     */    public function getDashboardStats()
    {
        $stats = [
            'totalRooms' => Room::count(),
            'availableRooms' => Room::where('room_status', 'available')->count(),
            'occupiedRooms' => Room::where('room_status', 'occupied')->count(),
            'maintenanceRooms' => Room::where('room_status', 'maintenance')->count(),
            'cleanRooms' => Room::where('cleaning_status', 'clean')->count(),
            'notCleanedRooms' => Room::where('cleaning_status', 'not_cleaned')->count(),
            'inProgressCleaningRooms' => Room::where('cleaning_status', 'in_progress')->count(),
            'lastUpdated' => now()->format('H:i:s')
        ];
        
        // Add check-in/check-out stats
        $checkinToday = Room::whereDate('checkin_time', now()->toDateString())->count();
        $checkoutToday = Room::whereDate('checkout_time', now()->toDateString())->count();
        
        $stats['checkedInToday'] = $checkinToday;
        $stats['checkedOutToday'] = $checkoutToday;
        $stats['todayActivity'] = $checkinToday + $checkoutToday;
        
        return response()->json($stats);
    }
    
    /**
     * Show static dashboard with pre-loaded chart data
     */
    public function staticDashboard()
    {
        // Get room category data
        $roomCategories = Room::with('roomCategory')
            ->get()
            ->groupBy(function($item) {
                return $item->roomCategory ? $item->roomCategory->category_name : 'Uncategorized';
            })
            ->map(function($items, $key) {
                return [
                    'category' => $key,
                    'count' => $items->count()
                ];
            })
            ->values()
            ->toArray();
            
        // Get monthly trends data
        $monthlyTrends = [];
        $currentMonth = now()->startOfMonth();
        
        for ($i = 5; $i >= 0; $i--) {
            $month = $currentMonth->copy()->subMonths($i);
            $monthName = $month->format('M Y');
            
            $monthlyCheckins = Room::whereMonth('checkin_time', $month->month)
                ->whereYear('checkin_time', $month->year)
                ->count();
                
            $monthlyCheckouts = Room::whereMonth('checkout_time', $month->month)
                ->whereYear('checkout_time', $month->year)
                ->count();
                
            $monthlyTrends[] = [
                'month' => $monthName,
                'checkins' => $monthlyCheckins,
                'checkouts' => $monthlyCheckouts
            ];
        }
        
        return view('dashboard-static', compact('roomCategories', 'monthlyTrends'));
    }
}
