<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::with('permissions')->get();
            return response()->json(['roles' => $roles]);
        }

        $permissions = Permission::all();
        $groupedPermissions = [];
        foreach ($permissions as $perm) {
            [$module, $action] = explode('.', $perm->name);
            $groupedPermissions[$module][$action] = $perm;
        }
        return view('roles.index', compact('permissions', 'groupedPermissions'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('roles.create')) {
            abort(403, 'Anda tidak punya akses untuk menyimpan roles');
        }
        $role = Role::create(['name' => $request->name,'guard_name' => 'web']);
        $role->syncPermissions($request->permissions ?? []);
        return response()->json(['message' => 'Role created']);
    }

    public function show($id)
    {
        if (!Auth::user()->can('roles.read')) {
            abort(403, 'Anda tidak punya akses untuk menyimpan roles');
        }
        $role = Role::findOrFail($id);
        $permissions = $role->permissions->pluck('name');

        return response()->json([
            'id' => $role->id,
            'name' => $role->name,
            'permissions' => $permissions
        ]);
    }

    public function update(Request $request, Role $role)
    {
        if (!Auth::user()->can('users.update')) {
            abort(403, 'Anda tidak punya akses untuk mengedit user');
        }
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);
        return response()->json(['message' => 'Role updated']);
    }

    public function destroy(Role $role)
    {
        if (!Auth::user()->can('roles.delete')) {
            abort(403, 'Anda tidak punya akses untuk mengedit user');
        }
        $role->delete();
        return response()->json(['message' => 'Role deleted']);
    }
}
