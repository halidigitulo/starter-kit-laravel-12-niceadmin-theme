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
            ],
            [
                'name' => 'Settings',
                'url' => '/settings',
                'icon' => 'settings-line',
                'permission' => null,
                'sort_order' => 2,
            ],
            [
                'name' => 'Users',
                'url' => '/users',
                'icon' => 'user-line',
                'permission' => 'users.read',
                'sort_order' => 3,
            ],
            [
                'name' => 'Roles',
                'url' => '/roles',
                'icon' => 'guide-line',
                'permission' => 'roles.read',
                'sort_order' => 4,
            ],
            [
                'name' => 'Menus',
                'url' => '/menus',
                'icon' => 'menu-line',
                'permission' => 'menus.read',
                'sort_order' => 5,
            ],
            [
                'name' => 'Permissions',
                'url' => '/permission',
                'icon' => 'circle-line',
                'permission' => 'permissions.read',
                'sort_order' => 6,
            ],
            [
                'name' => 'Profile',
                'url' => '/profile',
                'icon' => 'community-line',
                'permission' => null,
                'sort_order' => 7,
            ]
        ];

        foreach ($data as $item) {
            // $permission = Permission::firstOrCreate(['name' => $item['permission']]);

            Menu::firstOrCreate([
                'name' => $item['name'],
                'url' => $item['url'],
                'icon' => $item['icon'],
                'permission_name' => $item['permission'],
                'parent_id' => null,
                'sort_order' => $item['sort_order'],
                'is_protected' => 1
            ]);
        }
    }
}
