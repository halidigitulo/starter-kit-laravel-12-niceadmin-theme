<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\Menu;

class MenuPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $data = [
            [
                'name' => 'Dashboard',
                'url' => '/dashboard',
                'icon' => 'dashboard-2-line',
                'permission' => 'dashboard.read',
                'sort_order' => 1,
                'parent_id' => null,
            ],
            [
                'name' => 'Settings',
                'url' => '/settings',
                'icon' => 'settings-line',
                'permission' => null,
                'sort_order' => 2,
                'parent_id' => null
            ],
            [
                'name' => 'Users',
                'url' => '/users',
                'icon' => 'user-line',
                'permission' => 'users.read',
                'sort_order' => 1,
                'parent_id' => 2,
            ],
            [
                'name' => 'Roles',
                'url' => '/roles',
                'icon' => 'guide-line',
                'permission' => 'roles.read',
                'sort_order' => 2,
                'parent_id' => 2,
            ],
            [
                'name' => 'Menus',
                'url' => '/menus',
                'icon' => 'menu-line',
                'permission' => 'menus.read',
                'sort_order' => 3,
                'parent_id' => 2,
            ],
            [
                'name' => 'Permissions',
                'url' => '/permissions',
                'icon' => 'circle-line',
                'permission' => 'permissions.read',
                'sort_order' => 4,
                'parent_id' => 2,
            ],
            [
                'name' => 'Profile',
                'url' => '/profile',
                'icon' => 'community-line',
                'permission' => null,
                'sort_order' => 3,
                'parent_id' => null,
            ]
        ];

        foreach ($data as $item) {
            // $permission = Permission::firstOrCreate(['name' => $item['permission']]);

            Menu::firstOrCreate([
                'name' => $item['name'],
                'url' => $item['url'],
                'icon' => $item['icon'],
                'permission_name' => $item['permission'],
                'parent_id' => $item['parent_id'],
                'sort_order' => $item['sort_order'],
                'is_protected' => 1
            ]);
        }
    }
}
