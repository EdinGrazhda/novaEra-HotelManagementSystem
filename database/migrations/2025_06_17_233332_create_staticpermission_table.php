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
        // Devices permissions
        $permissions = [
            'doctorView',
            'doctorCreate',
            'doctorEdit',
            'doctorDelete',
            'medicineView',
            'medicineCreate',
            'medicineEdit',
            'medicineDelete',
            'userView',
            'userCreate',
            'userEdit',
            'userDelete'
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
            'doctorView',
            'doctorCreate',
            'doctorEdit',
            'doctorDelete',
            'medicineView',
            'medicineCreate',
            'medicineEdit',
            'medicineDelete',
            'userView',
            'userCreate',
            'userEdit',
            'userDelete'
        ];

        foreach ($permissions as $permission) {
            Permission::findByName($permission)->delete();
        }
    }
};
