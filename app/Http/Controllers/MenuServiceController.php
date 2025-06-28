<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Room;
use App\Models\RoomMenuOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuServiceController extends Controller
{
    /**
     * Display the livewire version of the menu service.
     */
    public function livewireIndex()
    {
        return view('menuService.livewire-index');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $rooms = Room::all();
        $menu_items = Menu::all();
        $orders = RoomMenuOrder::with(['room', 'menu'])
            ->when($request->has('status_filter') && !empty($request->status_filter), function($query) use ($request) {
                return $query->where('status', $request->status_filter);
            })
            ->when($request->has('room_filter') && !empty($request->room_filter), function($query) use ($request) {
                return $query->where('room_id', $request->room_filter);
            })
            ->when($request->has('search') && !empty($request->search), function($query) use ($request) {
                $search = $request->search;
                return $query->whereHas('menu', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('room', function($q) use ($search) {
                    $q->where('room_number', 'like', "%{$search}%");
                });
            })
            ->get();

        return view('menuService.index', compact('rooms', 'menu_items', 'orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'selected_items' => 'required|array',
            'selected_items.*' => 'required|exists:menu,id',
            'menu_items' => 'required|array',
            'notes' => 'nullable|string',
        ]);
        
        // Filter menu_items to only include selected items
        $selectedItemIds = $request->selected_items;
        $filteredItems = collect($request->menu_items)->filter(function ($item, $key) use ($selectedItemIds) {
            return in_array($key, $selectedItemIds);
        });

        if ($filteredItems->isEmpty()) {
            return redirect()->back()->with('error', 'No menu items were selected.')->withInput();
        }

        DB::beginTransaction();
        try {
            foreach ($filteredItems as $item) {
                // Ensure essential fields are present
                if (!isset($item['menu_id']) || !isset($item['quantity'])) {
                    continue;
                }
                
                RoomMenuOrder::create([
                    'room_id' => $request->room_id,
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['quantity'],
                    'status' => 'received',
                    'notes' => $request->notes ?? null,
                ]);
            }
            DB::commit();
            return redirect()->route('menuService.index')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to place order: ' . $e->getMessage())->withInput();
        }
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
        $request->validate([
            'status' => 'required|in:received,in_process,delivered',
        ]);

        $order = RoomMenuOrder::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->route('menuService.index')
            ->with('success', 'Order status updated successfully!');
    }

    /**
     * Update the status of a food order.
     */
    public function updateStatus(Request $request, RoomMenuOrder $roomMenuOrder)
    {
        $request->validate([
            'status' => 'required|in:received,in_process,delivered',
        ]);

        $roomMenuOrder->status = $request->status;
        $roomMenuOrder->save();

        return redirect()->route('menuService.index')
            ->with('success', 'Order status updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomMenuOrder $roomMenuOrder)
    {
        $roomMenuOrder->delete();

        return redirect()->route('menuService.index')
            ->with('success', 'Order cancelled successfully!');
    }
}
