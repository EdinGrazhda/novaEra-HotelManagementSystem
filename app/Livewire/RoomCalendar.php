<?php

namespace App\Livewire;

use App\Models\Room;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;

class RoomCalendar extends Component
{
    public $month;
    public $year;
    public $calendarData = [];
    public $statusFilter = 'all';
    public $categoryFilter = 'all';
    public $floorFilter = 'all';
    public $floors = [];
    public $categories = [];

    protected $queryString = [
        'month' => ['except' => ''],
        'year' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'categoryFilter' => ['except' => 'all'],
        'floorFilter' => ['except' => 'all'],
    ];

    public function mount($month = null, $year = null)
    {
        $this->month = $month ?: Carbon::now()->month;
        $this->year = $year ?: Carbon::now()->year;
        
        // Get unique floors for filtering
        $this->floors = Room::distinct('room_floor')
            ->pluck('room_floor')
            ->toArray();

        // Get room categories for filtering
        $this->categories = \App\Models\RoomCategory::pluck('category_name', 'id')
            ->toArray();
    }

    public function navigatePrevMonth()
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->subMonth();
        $this->month = $date->month;
        $this->year = $date->year;
    }

    public function navigateNextMonth()
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->addMonth();
        $this->month = $date->month;
        $this->year = $date->year;
    }

    public function navigateToday()
    {
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
    }

    public function updateStatusFilter($status)
    {
        $this->statusFilter = $status;
    }

    public function updateCategoryFilter($category)
    {
        $this->categoryFilter = $category;
    }

    public function updateFloorFilter($floor)
    {
        $this->floorFilter = $floor;
    }    private function generateCalendarData()
    {
        $startOfMonth = Carbon::createFromDate($this->year, $this->month, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($this->year, $this->month, 1)->endOfMonth();
        
        // Cache key based on current filters and date
        $cacheKey = "room_calendar_{$this->year}_{$this->month}_{$this->statusFilter}_{$this->categoryFilter}_{$this->floorFilter}";
        
        // Try to get data from cache for better performance
        if (cache()->has($cacheKey) && !app()->isLocal()) {
            return cache()->get($cacheKey);
        }
        
        // Get room data that matches our filters
        $roomsQuery = Room::query()
            ->with(['roomCategory']) // Eager load relationships
            ->orderBy('room_floor')
            ->orderBy('room_number');
        
        // Apply filters
        if ($this->statusFilter !== 'all') {
            $roomsQuery->where('room_status', $this->statusFilter);
        }
        
        if ($this->categoryFilter !== 'all') {
            $roomsQuery->where('room_category_id', $this->categoryFilter);
        }
        
        if ($this->floorFilter !== 'all') {
            $roomsQuery->where('room_floor', $this->floorFilter);
        }
        
        $rooms = $roomsQuery->get();
        
        // Generate days of the month
        $daysInMonth = CarbonPeriod::create($startOfMonth, $endOfMonth);
        $days = collect($daysInMonth)->map(function ($date) {
            return [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('j'),
                'isToday' => $date->isToday(),
                'isWeekend' => $date->isWeekend(),
            ];
        });

        $calendarData = [
            'days' => $days,
            'rooms' => $rooms->map(function ($room) use ($startOfMonth, $endOfMonth) {
                $roomData = [
                    'id' => $room->id,
                    'room_number' => $room->room_number,
                    'room_floor' => $room->room_floor,
                    'category_name' => $room->roomCategory ? $room->roomCategory->category_name : 'N/A',
                    'room_status' => $room->room_status,
                    'dates' => []
                ];
                
                // For each room, track status for each day of the month
                $period = CarbonPeriod::create($startOfMonth, $endOfMonth);
                
                foreach ($period as $date) {
                    $dateKey = $date->format('Y-m-d');
                    
                    // Default status is the current room status
                    $status = $room->room_status;
                    
                    // Check if the date falls between check-in and check-out times
                    if ($room->checkin_time && $room->checkout_time) {
                        $checkinDate = Carbon::parse($room->checkin_time)->startOfDay();
                        $checkoutDate = Carbon::parse($room->checkout_time)->startOfDay();
                        
                        if ($date->between($checkinDate, $checkoutDate)) {
                            $status = 'occupied';
                        }
                    } else if ($room->checkin_time && $room->checkin_status == 'checked_in' && !$room->checkout_time) {
                        // Room is checked in but not checked out yet
                        $checkinDate = Carbon::parse($room->checkin_time)->startOfDay();
                        
                        if ($date->greaterThanOrEqualTo($checkinDate)) {
                            $status = 'occupied';
                        }
                    }
                      // Check if this is a booking period day
                    $isBookingPeriod = false;
                    $isCheckedIn = false;
                    $isCheckedOut = false;
                    
                    if ($room->checkin_time) {
                        $checkinDate = Carbon::parse($room->checkin_time)->startOfDay();
                        $isCheckedIn = $checkinDate->isSameDay($date);
                        
                        if ($room->checkout_time) {
                            $checkoutDate = Carbon::parse($room->checkout_time)->startOfDay();
                            $isCheckedOut = $checkoutDate->isSameDay($date);
                            
                            // Consider any day between check-in and check-out as part of the booking
                            $isBookingPeriod = $date->between($checkinDate, $checkoutDate);
                        } else {
                            // If there's no checkout yet, consider all days from check-in onwards as booked
                            $isBookingPeriod = $date->greaterThanOrEqualTo($checkinDate);
                        }
                    }

                    $roomData['dates'][$dateKey] = [
                        'status' => $status,
                        'is_checked_in' => $isCheckedIn,
                        'is_checked_out' => $isCheckedOut,
                        'is_booking_period' => $isBookingPeriod,
                    ];
                }
                
                return $roomData;
            })
        ];        // Cache the data for 1 minute (avoids recalculation on every poll)
        if (!app()->isLocal()) {
            cache()->put($cacheKey, $calendarData, now()->addMinute());
        }
        
        return $calendarData;
    }    public function checkIn($roomId)
    {
        $room = Room::findOrFail($roomId);
        $room->checkin_status = 'checked_in';
        $room->checkin_time = now();
        $room->room_status = 'occupied';
        $room->save();
        
        // Clear cache to ensure fresh data
        $this->clearCalendarCache();
        
        $this->dispatch('statusUpdated', roomId: $roomId, action: 'checkin');
        
        // IMPORTANT: Direct dashboard refresh
        $this->dispatch('refresh-dashboard');
        
        logger()->info("Room Calendar: Room {$room->room_number} checked in - Dashboard refresh triggered");
        
        session()->flash('success', "Room {$room->room_number} checked in successfully.");
    }
    
    public function checkOut($roomId)
    {
        $room = Room::findOrFail($roomId);
        $room->checkout_status = 'checked_out';
        $room->checkout_time = now();
        $room->room_status = 'available';
        $room->cleaning_status = 'not_cleaned'; // Mark for cleaning after checkout
        $room->save();
        
        // Clear cache to ensure fresh data
        $this->clearCalendarCache();
        
        $this->dispatch('statusUpdated', roomId: $roomId, action: 'checkout');
        
        // IMPORTANT: Direct dashboard refresh
        $this->dispatch('refresh-dashboard');
        
        logger()->info("Room Calendar: Room {$room->room_number} checked out - Dashboard refresh triggered");
        
        session()->flash('success', "Room {$room->room_number} checked out successfully.");
    }
    
    /**
     * Clear all calendar cache entries for this view
     */
    private function clearCalendarCache()
    {
        // Clear cache for current month
        $cacheKey = "room_calendar_{$this->year}_{$this->month}_";
        cache()->forget($cacheKey . "all_all_all");
        cache()->forget($cacheKey . "{$this->statusFilter}_{$this->categoryFilter}_{$this->floorFilter}");
        
        // Also clear cache for navigation month if near end/start of month
        $today = Carbon::today();
        $endOfMonth = Carbon::createFromDate($this->year, $this->month, 1)->endOfMonth();
        $startOfMonth = Carbon::createFromDate($this->year, $this->month, 1)->startOfMonth();
        
        // If within 3 days of month end/start, clear adjacent month too
        if ($today->diffInDays($endOfMonth) <= 3) {
            $nextMonth = Carbon::createFromDate($this->year, $this->month, 1)->addMonth();
            $nextCacheKey = "room_calendar_{$nextMonth->year}_{$nextMonth->month}_";
            cache()->forget($nextCacheKey . "all_all_all");
        }
        
        if ($today->diffInDays($startOfMonth) <= 3) {
            $prevMonth = Carbon::createFromDate($this->year, $this->month, 1)->subMonth();
            $prevCacheKey = "room_calendar_{$prevMonth->year}_{$prevMonth->month}_";
            cache()->forget($prevCacheKey . "all_all_all");
        }
    }

    public function render()
    {
        $this->calendarData = $this->generateCalendarData();
        
        return view('livewire.room-calendar', [
            'currentMonth' => Carbon::createFromDate($this->year, $this->month, 1)->format('F Y'),
            'calendarData' => $this->calendarData
        ]);
    }
}
