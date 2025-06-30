<?php

use App\Http\Controllers\CleaningServiceController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\CalendarController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Contact routes
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');


Route::get('dashboard', function () {
    return view('dashboard');
})
    ->middleware(['auth', 'verified', 'can:view-dashboard'])
    ->name('dashboard');



Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');


    // Roles and Permissions Management
  
        // Roles
        Route::get('roles', [App\Http\Controllers\RolePermissionController::class, 'index'])->name('roles.index');
        Route::get('roles/create', [App\Http\Controllers\RolePermissionController::class, 'createRole'])->name('roles.create');
        Route::post('roles', [App\Http\Controllers\RolePermissionController::class, 'storeRole'])->name('roles.store');
        Route::get('roles/{role}/edit', [App\Http\Controllers\RolePermissionController::class, 'editRole'])->name('roles.edit');
        Route::put('roles/{role}', [App\Http\Controllers\RolePermissionController::class, 'updateRole'])->name('roles.update');
        Route::delete('roles/{role}', [App\Http\Controllers\RolePermissionController::class, 'destroyRole'])->name('roles.destroy');
        
        // User-Role assignment
        Route::get('users/{user}/roles', [App\Http\Controllers\RolePermissionController::class, 'editUserRoles'])->name('users.edit.roles');
        Route::put('users/{user}/roles', [App\Http\Controllers\RolePermissionController::class, 'updateUserRoles'])->name('users.update.roles');
        
        // Permissions
        Route::get('permissions/create', [App\Http\Controllers\RolePermissionController::class, 'createPermission'])->name('permissions.create');
        Route::post('permissions', [App\Http\Controllers\RolePermissionController::class, 'storePermission'])->name('permissions.store');
        
        // User Management
        Route::resource('users', UserController::class)->middleware('can:manage-users');

    //Rooms
    Route::get('rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('rooms/dashboard', App\Livewire\Dashboard::class)->name('rooms.dashboard');
    Route::get('rooms/update-statuses', [RoomController::class, 'updateRoomStatuses'])->name('rooms.updateStatuses');
    Route::patch('rooms/{room}/status', [RoomController::class, 'updateStatus'])->name('rooms.updateStatus');
    Route::patch('rooms/{room}/cleaning-status', [RoomController::class, 'updateCleaningStatus'])->name('rooms.updateCleaningStatus');
    Route::patch('rooms/{room}/check-in', [RoomController::class, 'checkIn'])->name('rooms.checkIn');
    Route::patch('rooms/{room}/check-out', [RoomController::class, 'checkOut'])->name('rooms.checkOut');
    Route::resource('rooms', RoomController::class)->except(['index']);


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
    Route::get('menuService', [App\Http\Controllers\MenuServiceController::class, 'livewireIndex'])->name('menuService.index');
    Route::get('menuService/legacy', [App\Http\Controllers\MenuServiceController::class, 'index'])->name('menuService.legacy');
    Route::post('menuService', [App\Http\Controllers\MenuServiceController::class, 'store'])->name('menuService.store');
    Route::patch('menuService/{roomMenuOrder}/update-status', [App\Http\Controllers\MenuServiceController::class, 'updateStatus'])->name('menuService.updateStatus');
    Route::delete('menuService/{roomMenuOrder}', [App\Http\Controllers\MenuServiceController::class, 'destroy'])->name('menuService.destroy');
    
    // Room Calendar
    Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');

});

require __DIR__.'/auth.php';
