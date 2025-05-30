<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomCategory;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display the rooms dashboard
     */
    public function dashboard()
    {
        $totalRooms = Room::count();
        $availableRooms = Room::where('room_status', 'available')->count();
        $occupiedRooms = Room::where('room_status', 'occupied')->count();
        
        return view('rooms.dashboard', compact('totalRooms', 'availableRooms', 'occupiedRooms'));
    }

    /**
     * Display a listing of the rooms.
     */
    public function index()
    {
        $rooms = Room::with('category')->get();
        return view('rooms.index', compact('rooms'));
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
        
        return redirect()->back()->with('success', $statusMessage);
    }

    /**
     * Remove the specified room from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('rooms.index')
                        ->with('success', 'Room deleted successfully');
    }
}
