<?php

namespace App\Livewire\Menu;

use App\Models\Menu;
use Livewire\Component;
use Livewire\WithPagination;

class MenuList extends Component
{
    use WithPagination;
    
    public $search = '';
    
    // This will trigger the search every time the search property changes
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        $query = Menu::query();
        
        // Apply search filter if provided
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            });
        }
        
        $menus = $query->get();
        
        return view('livewire.menu.menu-list', [
            'menus' => $menus
        ]);
    }
}
