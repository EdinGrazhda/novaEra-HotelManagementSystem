<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@novaera.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        $adminUser->assignRole('admin');

        // Create receptionist user
        $receptionistUser = User::firstOrCreate(
            ['email' => 'receptionist@novaera.com'],
            [
                'name' => 'Receptionist User',
                'password' => Hash::make('password'),
            ]
        );
        $receptionistUser->assignRole('receptionist');

        // Create cleaner user
        $cleanerUser = User::firstOrCreate(
            ['email' => 'cleaner@novaera.com'],
            [
                'name' => 'Cleaner User',
                'password' => Hash::make('password'),
            ]
        );
        $cleanerUser->assignRole('cleaner');

        // Create chef user
        $chefUser = User::firstOrCreate(
            ['email' => 'chef@novaera.com'],
            [
                'name' => 'Chef User',
                'password' => Hash::make('password'),
            ]
        );
        $chefUser->assignRole('chef');
    }
}
