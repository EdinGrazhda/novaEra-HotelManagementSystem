<?php

namespace App\Console\Commands;

use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateRoomStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rooms:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically update room statuses based on check-in and check-out times';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting automatic room status updates...');
        $now = Carbon::now();

        // Process check-ins: Find rooms that have check-in time in the past but are not marked as occupied
        $this->processCheckIns($now);
        
        // Process check-outs: Find rooms that have check-out time in the past but are still marked as occupied
        $this->processCheckOuts($now);
        
        $this->info('Room status updates completed successfully.');
        
        return Command::SUCCESS;
    }
    
    /**
     * Process rooms that should be checked in
     */
    private function processCheckIns($now)
    {
        $checkInRooms = Room::where('checkin_time', '<=', $now)
            ->where(function($query) {
                $query->where('room_status', '!=', 'occupied')
                      ->orWhere('checkin_status', '!=', 'checked_in');
            })
            ->get();
            
        $count = count($checkInRooms);
        $this->info("Found {$count} rooms due for check-in status update.");
        
        foreach ($checkInRooms as $room) {
            $room->room_status = 'occupied';
            $room->checkin_status = 'checked_in';
            $room->cleaning_status = 'clean';
            $room->save();
            
            $this->info("Room #{$room->room_number} has been marked as occupied (auto check-in).");
            Log::info("Automatic check-in processed for room #{$room->room_number}");
        }
    }
    
    /**
     * Process rooms that should be checked out
     */
    private function processCheckOuts($now)
    {
        $checkOutRooms = Room::where('checkout_time', '<=', $now)
            ->where(function($query) {
                $query->where('room_status', '=', 'occupied')
                      ->orWhere('checkout_status', '!=', 'checked_out');
            })
            ->get();
            
        $count = count($checkOutRooms);
        $this->info("Found {$count} rooms due for check-out status update.");
        
        foreach ($checkOutRooms as $room) {
            $room->room_status = 'available';
            $room->checkout_status = 'checked_out';
            $room->cleaning_status = 'not_cleaned';
            $room->save();
            
            $this->info("Room #{$room->room_number} has been marked as available and needs cleaning (auto check-out).");
            Log::info("Automatic check-out processed for room #{$room->room_number}");
        }
    }
}
