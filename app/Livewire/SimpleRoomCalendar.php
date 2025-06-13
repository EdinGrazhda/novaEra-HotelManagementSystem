<?php

namespace App\Livewire;

use App\Models\Room;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class SimpleRoomCalendar extends Component
{
    public $month;
    public $year;
    public $calendarData = [];
    
    protected $queryString = [
        'month' => ['except' => ''],
        'year' => ['except' => ''],
    ];
    
    public function mount($month = null, $year = null)
    {
        $this->month = $month ?: Carbon::now()->month;
        $this->year = $year ?: Carbon::now()->year;
        
        $this->loadCalendarData();
    }
    
    public function navigatePrevMonth()
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->subMonth();
        $this->month = $date->month;
        $this->year = $date->year;
        $this->loadCalendarData();
    }
    
    public function navigateNextMonth()
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->addMonth();
        $this->month = $date->month;
        $this->year = $date->year;
        $this->loadCalendarData();
    }
    
    public function loadCalendarData()
    {
        // Get the first day of the month
        $firstDay = Carbon::createFromDate($this->year, $this->month, 1);
        // Get the last day of the month
        $lastDay = Carbon::createFromDate($this->year, $this->month, 1)->endOfMonth();
        // Get the number of days in the month
        $daysInMonth = $lastDay->day;
        
        // Initialize calendar data
        $this->calendarData = [];
        
        // Get all rooms with check-in and check-out information within this month
        $rooms = Room::where(function($query) use ($firstDay, $lastDay) {
            $query->where(function($q) use ($firstDay, $lastDay) {
                // Check-in is within this month
                $q->whereNotNull('checkin_time')
                  ->where('checkin_time', '>=', $firstDay->format('Y-m-d'))
                  ->where('checkin_time', '<=', $lastDay->format('Y-m-d'));
            })->orWhere(function($q) use ($firstDay, $lastDay) {
                // Check-out is within this month
                $q->whereNotNull('checkout_time')
                  ->where('checkout_time', '>=', $firstDay->format('Y-m-d'))
                  ->where('checkout_time', '<=', $lastDay->format('Y-m-d'));
            })->orWhere(function($q) use ($firstDay, $lastDay) {
                // Period spans this month (check-in before, check-out after)
                $q->whereNotNull('checkin_time')
                  ->whereNotNull('checkout_time')
                  ->where('checkin_time', '<=', $firstDay->format('Y-m-d'))
                  ->where('checkout_time', '>=', $lastDay->format('Y-m-d'));
            });
        })
        ->get();
        
        // Initialize the calendar array for each day
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $this->calendarData[$day] = [
                'date' => Carbon::createFromDate($this->year, $this->month, $day),
                'isOccupied' => false,
                'rooms' => []
            ];
        }
        
        // Fill in occupancy data
        foreach ($rooms as $room) {
            if (!$room->checkin_time || !$room->checkout_time) {
                continue;
            }
            
            $checkInDate = Carbon::parse($room->checkin_time);
            $checkOutDate = Carbon::parse($room->checkout_time);
            
            // Adjust check-in date if it's before the first day of the month
            if ($checkInDate->lt($firstDay)) {
                $checkInDate = $firstDay->copy();
            }
            
            // Adjust check-out date if it's after the last day of the month
            if ($checkOutDate->gt($lastDay)) {
                $checkOutDate = $lastDay->copy();
            }
            
            // Mark all days between check-in and check-out as occupied
            $periodDays = $checkInDate->daysUntil($checkOutDate);
            foreach ($periodDays as $date) {
                if ($date->month == $this->month && $date->year == $this->year) {
                    $day = $date->day;
                    $this->calendarData[$day]['isOccupied'] = true;
                    $this->calendarData[$day]['rooms'][] = [
                        'id' => $room->id,
                        'room_number' => $room->room_number
                    ];
                }
            }
        }
    }
    
    public function render()
    {
        // Calculate the calendar grid
        $firstDayOfMonth = Carbon::createFromDate($this->year, $this->month, 1);
        $startingDayOfWeek = $firstDayOfMonth->dayOfWeek; // 0 (Sunday) to 6 (Saturday)
        $daysInMonth = $firstDayOfMonth->daysInMonth;
        $monthName = $firstDayOfMonth->format('F Y');
        
        return view('livewire.simple-room-calendar', [
            'calendarData' => $this->calendarData,
            'startingDayOfWeek' => $startingDayOfWeek,
            'daysInMonth' => $daysInMonth,
            'monthName' => $monthName
        ]);
    }
}
