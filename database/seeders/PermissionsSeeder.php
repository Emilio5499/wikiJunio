<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Permission::create(['name' => 'manage articles']);
        Permission::create(['name' => 'manage categories']);
        Permission::create(['name' => 'view articles']);

        $admin = Role::where('name', 'admin')->first();
        $admin->givePermissionTo(['manage articles', 'manage categories', 'view articles']);

        $editor = Role::where('name', 'editor')->first();
        $editor->givePermissionTo(['manage articles', 'view articles']);

        $reader = Role::where('name', 'reader')->first();
        $reader->givePermissionTo(['view articles']);
    }
}
