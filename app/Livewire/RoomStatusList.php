<?php

namespace App\Livewire;

use App\Models\Room;
use Livewire\Component;

class RoomStatusList extends Component
{
    public $statusFilter = 'all';
    public $cleaningFilter = 'all';
    public $searchQuery = '';
    
    protected $queryString = [
        'statusFilter' => ['except' => 'all'],
        'cleaningFilter' => ['except' => 'all'],
        'searchQuery' => ['except' => ''],
    ];
    
    public function mount($statusFilter = 'all', $cleaningFilter = 'all', $searchQuery = '')
    {
        $this->statusFilter = $statusFilter;
        $this->cleaningFilter = $cleaningFilter;
        $this->searchQuery = $searchQuery;
        
        // Update URL parameters on mount
        $this->dispatch('urlChanged', [
            'statusFilter' => $this->statusFilter !== 'all' ? $this->statusFilter : null,
            'cleaningFilter' => $this->cleaningFilter !== 'all' ? $this->cleaningFilter : null,
            'searchQuery' => !empty($this->searchQuery) ? $this->searchQuery : null
        ]);
    }
    
    public function updatedStatusFilter()
    {
        $this->dispatch('filterChanged');
        
        // Update URL parameter when filter changes
        $this->dispatch('urlChanged', [
            'statusFilter' => $this->statusFilter !== 'all' ? $this->statusFilter : null,
            'cleaningFilter' => $this->cleaningFilter !== 'all' ? $this->cleaningFilter : null,
            'searchQuery' => !empty($this->searchQuery) ? $this->searchQuery : null
        ]);
    }
    
    public function updatedCleaningFilter()
    {
        $this->dispatch('filterChanged');
        
        // Update URL parameter when filter changes
        $this->dispatch('urlChanged', [
            'statusFilter' => $this->statusFilter !== 'all' ? $this->statusFilter : null,
            'cleaningFilter' => $this->cleaningFilter !== 'all' ? $this->cleaningFilter : null,
            'searchQuery' => !empty($this->searchQuery) ? $this->searchQuery : null
        ]);
    }
    
    public function updatedSearchQuery()
    {
        $this->dispatch('filterChanged');
        
        // Update URL parameter when search changes
        $this->dispatch('urlChanged', [
            'statusFilter' => $this->statusFilter !== 'all' ? $this->statusFilter : null,
            'cleaningFilter' => $this->cleaningFilter !== 'all' ? $this->cleaningFilter : null,
            'searchQuery' => !empty($this->searchQuery) ? $this->searchQuery : null
        ]);
    }
    
    public function checkIn($roomId)
    {
        $room = Room::findOrFail($roomId);
        $room->checkin_status = 'checked_in';
        $room->checkin_time = now();
        $room->room_status = 'occupied';
        $room->save();
        
        $this->dispatch('statusUpdated', roomId: $roomId, action: 'checkin');
        session()->flash('success', "Room {$room->room_number} checked in successfully.");
    }
    
    public function checkOut($roomId)
    {
        $room = Room::findOrFail($roomId);
        $room->checkout_status = 'checked_out';
        $room->checkout_time = now();
        $room->room_status = 'available';
        $room->cleaning_status = 'not_cleaned'; 
        $room->save();
        
        $this->dispatch('statusUpdated', roomId: $roomId, action: 'checkout');
        session()->flash('success', "Room {$room->room_number} checked out successfully.");
    }

    public function render()
    {
        $roomsQuery = Room::query();
    
        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $roomsQuery->where('room_status', $this->statusFilter);
        }
        
        // Apply cleaning filter
        if ($this->cleaningFilter !== 'all') {
            $roomsQuery->where('cleaning_status', $this->cleaningFilter);
        }
        
        // Apply search query if provided
        if (!empty($this->searchQuery)) {
            $roomsQuery->where(function ($query) {
                $query->where('room_number', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('room_floor', 'like', '%' . $this->searchQuery . '%')
                    ->orWhereHas('roomCategory', function ($categoryQuery) {
                        $categoryQuery->where('category_name', 'like', '%' . $this->searchQuery . '%');
                    });
            });
        }
        
        $rooms = $roomsQuery->orderBy('room_number')->get();
        
        return view('livewire.room-status-list', [
            'rooms' => $rooms
        ]);
    }
}
