<?php

namespace App\Livewire;

use App\Models\Menu;
use App\Models\Room;
use App\Models\RoomMenuOrder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class RealTimeMenuService extends Component
{
    use WithPagination;
    
    // Use Tailwind theme for pagination
    protected $paginationTheme = 'tailwind';
    
    public $roomFilter = '';
    public $statusFilter = '';
    public $search = '';
    public $lastUpdated;
    public $pollingActive = true;

    public function mount($roomFilter = null, $statusFilter = null, $search = null)
    {
        $this->roomFilter = $roomFilter;
        $this->statusFilter = $statusFilter;
        $this->search = $search;
        $this->lastUpdated = now()->format('H:i:s');
    }

    public function poll()
    {
        $this->lastUpdated = now()->format('H:i:s');
        $this->dispatch('orders-updated', timestamp: $this->lastUpdated);
    }

    public function getOrdersProperty()
    {
        return RoomMenuOrder::with(['room', 'menu'])
            ->when($this->statusFilter, function($query) {
                return $query->where('status', $this->statusFilter);
            })
            ->when($this->roomFilter, function($query) {
                return $query->where('room_id', $this->roomFilter);
            })
            ->when($this->search, function($query) {
                $search = $this->search;
                return $query->where(function($q) use ($search) {
                    $q->whereHas('menu', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })->orWhereHas('room', function($q) use ($search) {
                        $q->where('room_number', 'like', "%{$search}%");
                    });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    #[On('food-order-updated')]
    public function handleOrderUpdate($orderId = null, $status = null)
    {
        $this->poll();
    }

    public function setStatusFilter($value)
    {
        $this->statusFilter = $value;
        $this->resetPage(); // Reset pagination when filter changes
    }

    public function setRoomFilter($value)
    {
        $this->roomFilter = $value;
        $this->resetPage(); // Reset pagination when filter changes
    }

    public function updatedSearch()
    {
        $this->resetPage(); // Reset pagination when search changes
    }

    public function updateOrderStatus($orderId, $status)
    {
        $order = RoomMenuOrder::find($orderId);
        if ($order) {
            $order->status = $status;
            $order->save();
            
            $this->dispatch('order-status-updated', [
                'orderId' => $orderId,
                'status' => $status
            ]);
            
            $this->poll();
        }
    }

    public function cancelOrder($orderId)
    {
        $order = RoomMenuOrder::find($orderId);
        if ($order) {
            $order->delete();
            $this->poll();
        }
    }

    public function createOrder($formData)
    {
        DB::beginTransaction();
        try {
            foreach ($formData['selected_items'] as $itemId) {
                RoomMenuOrder::create([
                    'room_id' => $formData['room_id'],
                    'menu_id' => $itemId,
                    'quantity' => $formData['quantities'][$itemId] ?? 1,
                    'status' => 'received',
                    'notes' => $formData['notes'] ?? null,
                ]);
            }
            DB::commit();
            
            session()->flash('success', 'Order placed successfully!');
            $this->poll();
            
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to place order: ' . $e->getMessage());
            return false;
        }
    }

    public function togglePolling()
    {
        $this->pollingActive = !$this->pollingActive;
    }

    public function getRoomsProperty()
    {
        return Room::all();
    }

    public function getMenuItemsProperty()
    {
        return Menu::all();
    }

    public function render()
    {
        return view('livewire.real-time-menu-service', [
            'orders' => $this->orders,
            'rooms' => $this->rooms,
            'menuItems' => $this->menuItems
        ]);
    }
}
