<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Room;
use Illuminate\Http\Request;

class CleaningServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cleaning=Room::all();
        return view('cleaning.index', compact('cleaning'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
    
    /**
     * Update the cleaning status of a room.
     */
    public function updateCleaningStatus(Request $request, Room $room)
    {
        try {
            // Log the incoming request data for debugging
            Log::info('Cleaning status update request', [
                'room_id' => $room->id,
                'request_data' => $request->all()
            ]);
            
            $validated = $request->validate([
                'cleaning_status' => 'required|in:not_cleaned,clean,in_progress',
                'cleaning_notes' => 'nullable|string|max:255',
            ]);
            
            // Log the validated data
            Log::info('Validated cleaning status data', [
                'room_id' => $room->id,
                'validated_data' => $validated
            ]);
            
            $room->update($validated);
            
      
            $room->refresh();
            
            Log::info('Cleaning status updated successfully', [
                'room_id' => $room->id,
                'cleaning_status' => $room->cleaning_status,
                'cleaning_notes' => $room->cleaning_notes,
            ]);
            
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cleaning status updated successfully',
                    'cleaning_status' => $room->cleaning_status,
                    'cleaning_notes' => $room->cleaning_notes,
                ]);
            }
            
            // For regular form submission, redirect back with success message
            return redirect()->back()->with('success', 'Cleaning status updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating cleaning status', [
                'room_id' => $room->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating cleaning status',
                    'error' => $e->getMessage(),
                ], 500);
            }
            
            // For regular form submission, redirect back with error
            return redirect()->back()->with('error', 'Error updating cleaning status: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
