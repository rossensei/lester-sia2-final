<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

        Permission::create(['name' => 'create post']);
        Permission::create(['name' => 'view post']);
        Permission::create(['name' => 'edit post']);
        Permission::create(['name' => 'delete post']);
        Permission::create(['name' => 'view all post']);
        Permission::create(['name' => 'ban user']);

        $admin->givePermissionTo(['view all post', 'ban user', 'delete post']);
        $user->givePermissionTo(['view post','create post', 'edit post', 'delete post']);

        $user = User::create([
            'name' => 'Rosalino Flores',
            'email' => 'fross0988@gmail.com',
            'password' => bcrypt('admin123'), // password
        ]);

        $allUsers = User::all();
        
        foreach($allUsers as $user) {
            $user->assignRole('user');
        }
        $admin1 = User::create([
            'name' => 'admin',
            'email' => 'admin@dev.fun',
            'password' => bcrypt('admin123'), // password
        ]);

        $admin1->assignRole('admin');
    }
}
