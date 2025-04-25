<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RolyUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $admin = Role::create(['name' => 'admin']);
        $editor = Role::create(['name' => 'editor']);
        $reader = Role::create(['name' => 'reader']);

        $user1 = User::factory()->create([
            'name' => 'Usuario admin',
            'email' => 'admin@wiki.com',
            'password' => bcrypt('password')
        ]);
        $user1->assignRole($admin);

        $user2 = User::factory()->create([
            'name' => 'Usuario editor',
            'email' => 'editor@wiki.com',
            'password' => bcrypt('password')
        ]);
        $user2->assignRole($editor);

        $user3 = User::factory()->create([
            'name' => 'Usuario lectura',
            'email' => 'reader@wiki.com',
            'password' => bcrypt('password')
        ]);
        $user3->assignRole($reader);
    }
}
