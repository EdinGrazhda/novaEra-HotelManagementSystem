<?php

use App\Http\Controllers\CleaningServiceController;
use App\Http\Controllers\MenuController;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\CalendarController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', function () {
    return view('dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');


    //Rooms
    Route::get('rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('rooms/dashboard', App\Livewire\Dashboard::class)->name('rooms.dashboard');
    Route::patch('rooms/{room}/status', [RoomController::class, 'updateStatus'])->name('rooms.updateStatus');
    Route::patch('rooms/{room}/cleaning-status', [RoomController::class, 'updateCleaningStatus'])->name('rooms.updateCleaningStatus');
    Route::patch('rooms/{room}/check-in', [RoomController::class, 'checkIn'])->name('rooms.checkIn');
    Route::patch('rooms/{room}/check-out', [RoomController::class, 'checkOut'])->name('rooms.checkOut');
    Route::resource('rooms', RoomController::class);


    //Cleaning Service
    Route::get('cleaning',[CleaningServiceController::class, 'index'])->name('cleaning.index');
    Route::patch('cleaning/{room}/update-status',[CleaningServiceController::class, 'updateCleaningStatus'])->name('cleaning.updateStatus');

    //Menu Management
    Route::get('menu', [MenuController::class, 'index'])->name('menu.index');
    Route::get('menu/create', [MenuController::class, 'create'])->name('menu.create');
    Route::post('menu', [MenuController::class, 'store'])->name('menu.store');
    Route::get('menu/{menu}', [MenuController::class, 'show'])->name('menu.show');
    Route::get('menu/{menu}/edit', [MenuController::class, 'edit'])->name('menu.edit');
    Route::put('menu/{menu}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('menu/{menu}', [MenuController::class, 'destroy'])->name('menu.destroy');
    

    //Menu Service
    Route::get('menuService', [App\Http\Controllers\MenuServiceController::class, 'index'])->name('menuService.index');
    Route::post('menuService', [App\Http\Controllers\MenuServiceController::class, 'store'])->name('menuService.store');
    Route::patch('menuService/{roomMenuOrder}/update-status', [App\Http\Controllers\MenuServiceController::class, 'updateStatus'])->name('menuService.updateStatus');
    Route::delete('menuService/{roomMenuOrder}', [App\Http\Controllers\MenuServiceController::class, 'destroy'])->name('menuService.destroy');
    
    // Room Calendar
    Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
});

require __DIR__.'/auth.php';
