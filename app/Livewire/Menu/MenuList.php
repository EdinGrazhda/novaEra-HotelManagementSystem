<?php

namespace App\Livewire\Menu;

use App\Models\Menu;
use Livewire\Component;
use Livewire\WithPagination;

class MenuList extends Component
{
    use WithPagination;
    
    // Use Tailwind theme for pagination
    protected $paginationTheme = 'tailwind';
    
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
        
        // Paginate with 5 items per page
        $menus = $query->paginate(5);
        
        return view('livewire.menu.menu-list', [
            'menus' => $menus
        ]);
    }
}
