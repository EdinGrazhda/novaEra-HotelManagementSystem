<?php

namespace App\Livewire;

use App\Models\Menu;
use App\Models\Room;
use App\Models\RoomMenuOrder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;

class RealTimeMenuService extends Component
{
    public $rooms;
    public $menuItems;
    public $orders;
    public $roomFilter = '';
    public $statusFilter = '';
    public $search = '';
    public $lastUpdated;
    public $pollingActive = true;

    public function mount()
    {
        $this->rooms = Room::all();
        $this->menuItems = Menu::all();
        $this->loadOrders();
        $this->lastUpdated = now()->format('H:i:s');
    }

    public function loadOrders()
    {
        $this->orders = RoomMenuOrder::with(['room', 'menu'])
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
            ->get();
            
        $this->lastUpdated = now()->format('H:i:s');
        $this->dispatch('orders-updated', timestamp: $this->lastUpdated);
    }

    #[On('food-order-updated')]
    public function handleOrderUpdate($orderId = null, $status = null)
    {
        $this->loadOrders();
    }

    public function setStatusFilter($value)
    {
        $this->statusFilter = $value;
        $this->loadOrders();
    }

    public function setRoomFilter($value)
    {
        $this->roomFilter = $value;
        $this->loadOrders();
    }

    public function updatedSearch()
    {
        $this->loadOrders();
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
            
            $this->loadOrders();
        }
    }

    public function cancelOrder($orderId)
    {
        $order = RoomMenuOrder::find($orderId);
        if ($order) {
            $order->delete();
            $this->loadOrders();
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
            $this->loadOrders();
            
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to place order: ' . $e->getMessage());
            return false;
        }
    }

    public function poll()
    {
        if ($this->pollingActive) {
            $this->loadOrders();
        }
    }

    public function togglePolling()
    {
        $this->pollingActive = !$this->pollingActive;
    }

    public function render()
    {
        return view('livewire.real-time-menu-service');
    }
}
