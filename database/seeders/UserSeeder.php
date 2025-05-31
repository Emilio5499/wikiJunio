<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $manageArticles = Permission::firstOrCreate(['name' => 'manage articles']);
        $manageCategories = Permission::firstOrCreate(['name' => 'manage categories']);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole  = Role::firstOrCreate(['name' => 'user']);

        $adminRole->givePermissionTo([$manageArticles, $manageCategories]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Usuario admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole($adminRole);

        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Usuario normal',
                'password' => Hash::make('password'),
            ]
        );
        $user->assignRole($userRole);
    }
}
