<div wire:poll.5s class="bg-white rounded-lg shadow-lg p-4">
    <style>
        /* Calendar styles */
        .calendar-container {
            max-width: 100%;
            overflow-x: auto;
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, minmax(100px, 1fr));
            gap: 2px;
        }
        
        .calendar-header {
            background-color: #f3f4f6;
            text-align: center;
            padding: 8px;
            font-weight: 600;
        }          .calendar-cell {
            height: 95px;
            border: 1px solid #e5e7eb;
            padding: 5px;
            position: relative;
            overflow: hidden;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .calendar-cell-empty {
            background-color: #f9fafb;
        }
        
        .calendar-cell-available {
            background-color: #d1fae5;
            transition: all 0.2s ease-in-out;
        }
        
        .calendar-cell-available:hover {
            background-color: #a7f3d0;
        }
        
        .calendar-cell-occupied {
            background-color: #fee2e2;
            transition: all 0.2s ease-in-out;
        }
        
        .calendar-cell-occupied:hover {
            background-color: #fecaca;
        }
          .cell-date {
            font-weight: 600;
            font-size: 0.875rem;
            position: absolute;
            top: 4px;
            left: 4px;
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 4px;
            padding: 1px 5px;
            min-width: 1.5rem;
            text-align: center;
        }.room-list {
            margin-top: 20px;
            font-size: 0.75rem;
            max-height: 55px;
            overflow-y: auto;
            scrollbar-width: thin; /* Firefox */
            padding-right: 2px;
            transition: all 0.3s ease;
            border-radius: 4px;
        }
        
        .room-list:hover {
            background-color: rgba(0,0,0,0.03);
        }
        
        .room-list::-webkit-scrollbar {
            width: 5px;
        }
        
        .room-list::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.03);
            border-radius: 4px;
        }
        
        .room-list::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.15);
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .room-list::-webkit-scrollbar-thumb:hover {
            background: rgba(0,0,0,0.25);
        }
        
        .month-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .month-title {
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .nav-button {
            background-color: #f3f4f6;
            border: 1px solid #e5e7eb;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease-in-out;
        }
        
        .nav-button:hover {
            background-color: #e5e7eb;
        }
    </style>

    <div class="month-navigation">
        <button wire:click="navigatePrevMonth" class="nav-button">
            &laquo; Previous Month
        </button>
        <h2 class="month-title">{{ $monthName }}</h2>
        <button wire:click="navigateNextMonth" class="nav-button">
            Next Month &raquo;
        </button>
    </div>

    <div class="calendar-container">
        <div class="calendar-grid">
            <!-- Calendar header - Days of week -->
            <div class="calendar-header">Sunday</div>
            <div class="calendar-header">Monday</div>
            <div class="calendar-header">Tuesday</div>
            <div class="calendar-header">Wednesday</div>
            <div class="calendar-header">Thursday</div>
            <div class="calendar-header">Friday</div>
            <div class="calendar-header">Saturday</div>
            
            <!-- Empty cells for the days before the first of the month -->
            @for ($i = 0; $i < $startingDayOfWeek; $i++)
                <div class="calendar-cell calendar-cell-empty"></div>
            @endfor
            
            <!-- Calendar days -->
            @for ($day = 1; $day <= $daysInMonth; $day++)
                <div class="calendar-cell {{ $calendarData[$day]['isOccupied'] ? 'calendar-cell-occupied' : 'calendar-cell-available' }}">
                    <div class="cell-date">{{ $day }}</div>                      @if (count($calendarData[$day]['rooms']) > 0)
                        <div class="room-list">                            @foreach ($calendarData[$day]['rooms'] as $room)
                                <div class="text-xs py-1 px-2 mb-1 bg-white rounded shadow-sm border-l-2 border-red-300 hover:border-l-4 transition-all duration-200">Room {{ $room['room_number'] }}</div>
                            @endforeach
                        </div>                        @if(count($calendarData[$day]['rooms']) > 4)
                            <div class="absolute bottom-0 right-0 bg-gradient-to-l from-gray-200 to-transparent text-xs px-2 py-0.5 text-gray-700 rounded-tl font-medium shadow-sm border-t border-l border-gray-100">
                                {{ count($calendarData[$day]['rooms']) }} rooms
                            </div>
                        @endif
                    @endif
                </div>
            @endfor
            
            <!-- Empty cells for the days after the last day of the month -->
            @php
                $totalCells = $startingDayOfWeek + $daysInMonth;
                $endingCells = 7 - ($totalCells % 7);
                if ($endingCells < 7) {
                    for ($i = 0; $i < $endingCells; $i++) {
                        echo '<div class="calendar-cell calendar-cell-empty"></div>';
                    }
                }
            @endphp
        </div>
    </div>
    
    <div class="mt-4">
        <div class="flex items-center mb-2">
            <div class="w-4 h-4 bg-green-200 mr-2"></div>
            <span class="text-sm">Available</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 bg-red-200 mr-2"></div>
            <span class="text-sm">Occupied (Check-in to Check-out)</span>
        </div>
    </div>
</div>
