<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomCategory;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;
    
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the dashboard view with initial data
     */
    public function index()
    {
        // Get initial data for charts
        $roomCategories = $this->getRoomCategories();
        $monthlyTrends = $this->getMonthlyTrends();
        
        return view('dashboard', compact('roomCategories', 'monthlyTrends'));
    }
    
    /**
     * Get room categories distribution data
     */
    public function getRoomCategories()
    {
        $categories = RoomCategory::withCount('rooms')
            ->orderBy('rooms_count', 'desc')
            ->get()
            ->map(function ($category) {
                return [
                    'category' => $category->name,
                    'count' => $category->rooms_count,
                ];
            });
            
        return $categories;
    }
    
    /**
     * API endpoint for room categories
     */
    public function apiRoomCategories()
    {
        return response()->json($this->getRoomCategories());
    }
    
    /**
     * Get monthly check-in/check-out trends
     */
    public function getMonthlyTrends()
    {
        // Use the existing service method
        return $this->dashboardService->getMonthlyCheckinCheckoutTrends();
    }
    
    /**
     * API endpoint for monthly trends
     */
    public function apiMonthlyTrends()
    {
        return response()->json($this->getMonthlyTrends());
    }
}
