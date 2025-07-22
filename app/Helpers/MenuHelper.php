
<?php

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

if (! function_exists('getAccessibleMenus')) {
    // function getAccessibleMenus()
    // {
    //     $menus = Menu::whereNull('parent_id')
    //         ->with(['children' => function ($query) {
    //             $query->orderBy('sort_order')->orderBy('name');
    //         }])
    //         ->orderBy('menu_group')
    //         ->orderBy('sort_order')
    //         ->orderBy('name')
    //         ->get();

    //     return $menus->filter(function ($menu) {
    //         // Filter induk menu jika memiliki permission atau child yang bisa diakses
    //         $canAccess = $menu->permission_name === null || Auth::user()?->can($menu->permission_name);

    //         $menu->children = $menu->children->filter(function ($child) {
    //             return $child->permission_name === null || auth()->user()?->can($child->permission_name);
    //         });

    //         // Tampilkan menu jika:
    //         // - punya permission, atau
    //         // - punya child yang boleh diakses
    //         return $canAccess || $menu->children->isNotEmpty();
    //     });
    // }

    function getAccessibleMenus()
    {
        $menus = Menu::whereNull('parent_id')
            ->with('children')
            ->orderBy('menu_group')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        // return $menus;

        return $menus->filter(function ($menu) {
            return $menu->permission_name === null || Auth::user()?->can($menu->permission_name);
        })->map(function ($menu) {
            $menu->children = $menu->children->filter(function ($child) {
                return $child->permission_name === null || Auth::user()?->can($child->permission_name);
            });
            return $menu;
        });
        // return $menus;
    }
}
