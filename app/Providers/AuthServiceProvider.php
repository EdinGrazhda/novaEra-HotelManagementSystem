<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\Room;
use App\Models\RoomMenuOrder;
use App\Models\User;
use App\Policies\MenuPolicy;
use App\Policies\RoomMenuOrderPolicy;
use App\Policies\RoomPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Room::class => RoomPolicy::class,
        Menu::class => MenuPolicy::class,
        RoomMenuOrder::class => RoomMenuOrderPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates based on permissions
        Gate::before(function (User $user, $ability) {
            // Admin has all permissions
            if ($user->hasRole('admin')) {
                return true;
            }
        });

        // Dashboard gate
        Gate::define('view-dashboard', function (User $user) {
            return $user->hasPermissionTo('dashboardView');
        });

        // Room gates
        Gate::define('view-rooms', function (User $user) {
            return $user->hasPermissionTo('roomsView');
        });
        
        Gate::define('create-room', function (User $user) {
            return $user->hasPermissionTo('roomsCreate');
        });
        
        Gate::define('edit-room', function (User $user) {
            return $user->hasPermissionTo('roomsEdit');
        });
        
        Gate::define('delete-room', function (User $user) {
            return $user->hasPermissionTo('roomsDelete');
        });

        // Menu gates
        Gate::define('view-menu', function (User $user) {
            return $user->hasPermissionTo('menuView');
        });
        
        Gate::define('create-menu', function (User $user) {
            return $user->hasPermissionTo('menuCreate');
        });
        
        Gate::define('edit-menu', function (User $user) {
            return $user->hasPermissionTo('menuEdit');
        });
        
        Gate::define('delete-menu', function (User $user) {
            return $user->hasPermissionTo('menuDelete');
        });

        // Cleaning gates
        Gate::define('view-cleaning', function (User $user) {
            return $user->hasPermissionTo('cleaningView');
        });
        
        Gate::define('update-cleaning-status', function (User $user) {
            return $user->hasPermissionTo('cleaningStatusUpdate');
        });

        // Menu order gates
        Gate::define('create-menu-order', function (User $user) {
            return $user->hasPermissionTo('menuCreateOrder');
        });        Gate::define('edit-menu-order', function (User $user) {
            return $user->hasPermissionTo('menuOrderEdit');
        });
        
        // Role management gate
        Gate::define('manage-roles', function (User $user) {
            return $user->hasPermissionTo('manage-roles');
        });
    }
}
