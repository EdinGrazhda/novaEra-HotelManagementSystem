<?php

namespace App\Livewire;

use App\Models\Room;
use Livewire\Component;

class CleaningStatusList extends Component
{
    public $cleaningFilter = 'all';
    public $searchQuery = '';
    
    protected $queryString = [
        'cleaningFilter' => ['except' => 'all'],
        'searchQuery' => ['except' => ''],
    ];
    
    public function mount($cleaningFilter = 'all', $searchQuery = '')
    {
        $this->cleaningFilter = $cleaningFilter;
        $this->searchQuery = $searchQuery;
        
        // Update URL parameters on mount
        $this->dispatch('urlChanged', [
            'cleaningFilter' => $this->cleaningFilter !== 'all' ? $this->cleaningFilter : null,
            'searchQuery' => !empty($this->searchQuery) ? $this->searchQuery : null
        ]);
    }
    
    public function updatedCleaningFilter()
    {
        $this->dispatch('filterChanged');
        
       
        $this->dispatch('urlChanged', [
            'cleaningFilter' => $this->cleaningFilter !== 'all' ? $this->cleaningFilter : null,
            'searchQuery' => !empty($this->searchQuery) ? $this->searchQuery : null
        ]);
    }
    
    public function updatedSearchQuery()
    {
        $this->dispatch('filterChanged');
        
        
        $this->dispatch('urlChanged', [
            'cleaningFilter' => $this->cleaningFilter !== 'all' ? $this->cleaningFilter : null,
            'searchQuery' => !empty($this->searchQuery) ? $this->searchQuery : null
        ]);
    }
    
    public function updateCleaningStatus($roomId, $status, $notes = null)
    {
        $room = Room::findOrFail($roomId);
        
        $room->cleaning_status = $status;
        
        if ($notes !== null) {
            $room->cleaning_notes = $notes;
        }
        
        $room->save();
        
        $this->dispatch('statusUpdated', roomId: $roomId, status: $status);
        session()->flash('success', "Room {$room->room_number} cleaning status updated to " . ucfirst(str_replace('_', ' ', $status)));
    }

    public function render()
    {
        $cleaningQuery = Room::query();
        
        // Apply cleaning filter
        if ($this->cleaningFilter !== 'all') {
            $cleaningQuery->where('cleaning_status', $this->cleaningFilter);
        }
        
        // Apply search query if provided
        if (!empty($this->searchQuery)) {
            $cleaningQuery->where(function ($query) {
                $query->where('room_number', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('room_floor', 'like', '%' . $this->searchQuery . '%')
                    ->orWhereHas('roomCategory', function ($categoryQuery) {
                        $categoryQuery->where('category_name', 'like', '%' . $this->searchQuery . '%');
                    });
            });
        }
        
        $cleaning = $cleaningQuery->orderBy('room_number')->get();
        
        return view('livewire.cleaning-status-list', [
            'cleaning' => $cleaning
        ]);
    }
}
