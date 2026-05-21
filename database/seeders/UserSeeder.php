<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;


class UserSeeder extends Seeder{

    public function run()
    {
        // Ensure roles exist with verb-based claims
        $superAdminRole = \App\Models\Role::updateOrCreate(
            ['name' => 'super admin'],
            ['claims' => [
                'manages-products', 'manages-orders', 'manages-customers', 
                'views-reports', 'manages-settings', 'manages-roles', 
                'manages-banners', 'manages-collections'
            ]]
        );

        $adminRole = \App\Models\Role::updateOrCreate(
            ['name' => 'admin'],
            ['claims' => [
                'manages-products', 'manages-orders', 'manages-customers', 
                'views-reports', 'manages-banners', 'manages-collections'
            ]]
        );

        $managerRole = \App\Models\Role::updateOrCreate(
            ['name' => 'manager'],
            ['claims' => [
                'manages-products', 'manages-orders', 'manages-customers', 'views-reports'
            ]]
        );

        \App\Models\Role::firstOrCreate(['name' => 'user'], ['claims' => []]);

        // Create the specific admin user
        $admin = User::firstOrCreate(
            ['email' => 'amin@example.com'],
            [
                'name' => 'Admin User',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign admin role if not already assigned
        if (!$admin->roles()->where('name', 'admin')->exists()) {
            $admin->roles()->attach($adminRole);
        }

        User::factory()->count(10)->create();
    }

     
}