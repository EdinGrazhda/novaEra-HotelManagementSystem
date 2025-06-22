<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomCategory;
use Illuminate\Http\Request;
use App\Models\RoomMenuOrder;

class RoomController extends Controller
{
    /**
     * Constructor to apply authorization middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Add role-based middleware for specific actions
        $this->authorizeResource(Room::class, 'room');
    }
    
    /**
     * Display the rooms dashboard with comprehensive statistics
     */
    public function dashboard()
    {
        // Basic room counts
        $totalRooms = Room::count();
        $availableRooms = Room::where('room_status', 'available')->count();
        $occupiedRooms = Room::where('room_status', 'occupied')->count();
        $maintenanceRooms = Room::where('room_status', 'maintenance')->count();
        
        // Cleaning status counts
        $cleanRooms = Room::where('cleaning_status', 'clean')->count();
        $notCleanedRooms = Room::where('cleaning_status', 'not_cleaned')->count();
        $inProgressCleaningRooms = Room::where('cleaning_status', 'in_progress')->count();
        
        // Check-in/Check-out statistics
        $checkedInToday = Room::whereDate('checkin_time', now()->toDateString())->count();
        $checkedOutToday = Room::whereDate('checkout_time', now()->toDateString())->count();
        $pendingCheckouts = Room::where('checkin_status', 'checked_in')
            ->where('checkout_status', '!=', 'checked_out')
            ->count();
        
        // Menu order statistics
        $totalFoodOrders = RoomMenuOrder::count();
        $activeOrders = RoomMenuOrder::where('status', '!=', 'delivered')->count();
        $deliveredOrders = RoomMenuOrder::where('status', 'delivered')->count();
        
        // Monthly statistics for check-ins and check-outs (for charts)
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
        
        return view('rooms.dashboard', compact(
            'totalRooms', 
            'availableRooms', 
            'occupiedRooms', 
            'maintenanceRooms',
            'cleanRooms',
            'notCleanedRooms',
            'inProgressCleaningRooms', 
            'checkedInToday',
            'checkedOutToday',
            'pendingCheckouts',
            'totalFoodOrders',
            'activeOrders',
            'deliveredOrders',
            'monthlyData',
            'roomsByCategory'
        ));
    }

    /**
     * Display a listing of the rooms with filtering.
     */
    public function index(Request $request)
    {
        $query = Room::with('category');
        
        // Support both URL parameter formats (status_filter from traditional filters and statusFilter from Livewire)
        $statusFilter = $request->status_filter ?? $request->statusFilter ?? 'all';
        $cleaningFilter = $request->cleaning_filter ?? $request->cleaningFilter ?? 'all';
        $categoryFilter = $request->category_filter ?? $request->categoryFilter ?? 'all';
        $searchQuery = $request->search ?? $request->searchQuery ?? '';
        
        // Apply status filter
        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('room_status', $statusFilter);
        }
        
        // Apply cleaning status filter
        if ($cleaningFilter && $cleaningFilter !== 'all') {
            $query->where('cleaning_status', $cleaningFilter);
        }
        
        // Apply search filter
        if (!empty($searchQuery)) {
            $search = $searchQuery;
            $query->where(function($q) use ($search) {
                $q->where('room_number', 'like', "%{$search}%")
                  ->orWhere('room_floor', 'like', "%{$search}%")
                  ->orWhereHas('roomCategory', function($q2) use ($search) {
                      $q2->where('category_name', 'like', "%{$search}%");
                  });
            });
        }
        
        $rooms = $query->get();
        
        return view('rooms.index', compact(
            'rooms', 
            'statusFilter', 
            'cleaningFilter', 
            'searchQuery'
        ));
    }

    /**
     * Show the form for creating a new room.
     */
    public function create()
    {
        $categories = RoomCategory::all();
        return view('rooms.create', compact('categories'));
    }

    /**
     * Store a newly created room in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_number' => 'required|string|unique:rooms',
            'room_floor' => 'required|string',
            'room_type' => 'required|in:single,double,suite',
            'room_status' => 'required|in:available,occupied,maintenance',
            'room_description' => 'nullable|string',
            'room_category_id' => 'required|exists:roomcategory,id',
        ]);

        Room::create($request->all());

        return redirect()->route('rooms.index')
                        ->with('success', 'Room created successfully.');
    }

    /**
     * Display the specified room.
     */
    public function show(Room $room)
    {
        return view('rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified room.
     */
    public function edit(Room $room)
    {
        $categories = RoomCategory::all();
        return view('rooms.edit', compact('room', 'categories'));
    }

    /**
     * Update the specified room in storage.
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_number' => 'required|string|unique:rooms,room_number,'.$room->id,
            'room_floor' => 'required|string',
            'room_type' => 'required|in:single,double,suite',
            'room_status' => 'required|in:available,occupied,maintenance',
            'room_description' => 'nullable|string',
            'room_category_id' => 'required|exists:roomcategory,id',
        ]);

        $room->update($request->all());

        return redirect()->route('rooms.index')
                        ->with('success', 'Room updated successfully');
    }    /**
     * Update the status of the specified room.
     */
    public function updateStatus(Request $request, Room $room)
    {
        $request->validate([
            'room_status' => 'required|in:available,occupied,maintenance',
        ]);

        $oldStatus = $room->room_status;
        $newStatus = $request->room_status;
        
        $updateData = [
            'room_status' => $newStatus
        ];
        
      
        if ($oldStatus === 'occupied' && $newStatus === 'available') {
            $updateData['cleaning_status'] = 'not_cleaned';
        }
        
      
        if ($newStatus === 'occupied') {
            $updateData['cleaning_status'] = 'clean';
        }
        
   
        if ($oldStatus === 'maintenance' && $newStatus === 'available') {
            $updateData['cleaning_status'] = 'clean';
        }
        
        $room->update($updateData);

        $statusMessage = 'Room status changed from ' . ucfirst($oldStatus) . ' to ' . ucfirst($newStatus) . ' successfully.';
        
       
        if (isset($updateData['cleaning_status'])) {
            $cleaningStatusText = str_replace('_', ' ', $updateData['cleaning_status']);
            $statusMessage .= ' Cleaning status set to ' . ucfirst($cleaningStatusText) . '.';
        }
        
        return redirect()->route('rooms.show', $room)
                        ->with('success', $statusMessage);
    }
    
    /**
     * Update the cleaning status of a room.
     */
    public function updateCleaningStatus(Request $request, Room $room)
    {
        $request->validate([
            'cleaning_status' => 'required|in:not_cleaned,clean,in_progress',
            'cleaning_notes' => 'nullable|string|max:255',
        ]);
        
        $oldStatus = $room->cleaning_status ?? 'clean';
        $newStatus = $request->cleaning_status;
        
        $updateData = [
            'cleaning_status' => $newStatus
        ];
        
        // Add cleaning notes if provided
        if ($request->has('cleaning_notes')) {
            $updateData['cleaning_notes'] = $request->cleaning_notes;
        }
        
        $room->update($updateData);
        
        $cleaningStatusText = str_replace('_', ' ', $newStatus);
        $statusMessage = 'Cleaning status changed to ' . ucfirst($cleaningStatusText) . ' successfully.';
        
        // Use JavaScript to trigger a browser event to refresh the dashboard
        return redirect()->back()
            ->with('success', $statusMessage)
            ->with('triggerDashboardRefresh', true); // This will be used in the view to trigger a JavaScript event
    }

    /**
     * Remove the specified room from storage.
     */
    public function destroy(Room $room)
    {
        try {
            // Delete any related room menu orders first
            $room->menuOrders()->delete();
            
            // Now we can safely delete the room
            $room->delete();

            return redirect()->route('rooms.index')
                            ->with('success', 'Room and all related orders deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('rooms.index')
                            ->with('error', 'Failed to delete room: ' . $e->getMessage());
        }
    }
    
    /**
     * Update the room check-in status.
     */
    public function checkIn(Room $room)
    {
        $room->update([
            'checkin_status' => 'checked_in',
            'checkin_time' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Room has been checked in successfully.');
    }
    
    /**
     * Update the room check-out status.
     */
    public function checkOut(Room $room)
    {
        $room->update([
            'checkout_status' => 'checked_out',
            'checkout_time' => now(),
        ]);
        
        // Dispatch Livewire events to refresh both room status and cleaning status lists
        event(new \Illuminate\Database\Eloquent\Events\Updated($room));
        
        return redirect()->back()->with('success', 'Room has been checked out successfully. Cleaning status set to not cleaned.');
    }
    
    /**
     * Manually trigger room status updates based on check-in and check-out times.
     * This can be used for testing or manual updates.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRoomStatuses()
    {
        // Call the command directly
        \Artisan::call('rooms:update-statuses');
        
        // Get the output
        $output = \Artisan::output();
        
        return redirect()->back()->with('success', 'Room statuses have been updated successfully. ' . $output);
    }
}
