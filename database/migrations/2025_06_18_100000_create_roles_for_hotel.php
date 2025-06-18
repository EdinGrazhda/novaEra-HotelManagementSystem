<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $receptionistRole = Role::create(['name' => 'receptionist']);
        $cleanerRole = Role::create(['name' => 'cleaner']);
        $chefRole = Role::create(['name' => 'chef']);

        // Get all permissions
        $permissions = Permission::all();

        // Assign all permissions to admin
        $adminRole->givePermissionTo($permissions);

        // Receptionist permissions
        $receptionistRole->givePermissionTo([
            'dashboardView',
            'roomsView',
            'roomsCreate',
            'roomsEdit',
            'cleaningView',
            'menuView',
            'menuCreateOrder',
        ]);

        // Cleaner permissions
        $cleanerRole->givePermissionTo([
            'dashboardView',
            'cleaningView',
            'cleaningStatusUpdate',
        ]);        // Chef permissions
        $chefRole->givePermissionTo([
            'dashboardView',
            'menuView',
            'menuCreate',
            'menuEdit',
            'menuOrderEdit',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Find roles
        $roles = ['admin', 'receptionist', 'cleaner', 'chef'];
        
        foreach ($roles as $roleName) {
            $role = Role::findByName($roleName);
            if ($role) {
                $role->delete();
            }
        }
    }
};
