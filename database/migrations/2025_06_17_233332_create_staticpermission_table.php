<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
    public function up(): void
    {
        // System permissions
        $permissions = [
           'dashboardView',
           'roomsView',
           'roomsCreate',
           'roomsEdit',
           'roomsDelete',
           'menuView',
           'menuCreate',
           'menuEdit',
           'menuDelete',
           'cleaningView',
           'cleaningStatusUpdate',
           'menuCreateOrder',
           'menuOrderEdit',
           'manage-roles',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permissions = [
            'dashboardView',
           'roomsView',
           'roomsCreate',
           'roomsEdit',
           'roomsDelete',
           'menuView',
           'menuCreate',
           'menuEdit',
           'menuDelete',
           'cleaningView',
           'cleaningStatusUpdate',
           'menuCreateOrder',
           'menuOrderEdit',
           'manage-roles',
        ];

        foreach ($permissions as $permission) {
            Permission::findByName($permission)->delete();
        }
    }
};
