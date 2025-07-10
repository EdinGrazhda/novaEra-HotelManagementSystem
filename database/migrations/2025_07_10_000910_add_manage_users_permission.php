<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the manage-users permission if it doesn't exist
        $permission = Permission::firstOrCreate(['name' => 'manage-users']);
        
        // Assign this permission to the admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo('manage-users');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the permission
        $permission = Permission::where('name', 'manage-users')->first();
        if ($permission) {
            $permission->delete();
        }
    }
};
