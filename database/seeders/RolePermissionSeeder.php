<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

        $permissions = ['dashboard.read','settings.read','roles.create','roles.read','roles.update','roles.delete','users.read','users.create','users.update','users.delete','menus.create','menus.read','menus.update','menus.delete','profile.create','profile.read','profile.update','permissions.create','permissions.read','permissions.update','permissions.delete'];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $admin->givePermissionTo($permissions);
    }
}
