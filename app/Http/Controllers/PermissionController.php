<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if (request()->ajax()) {
            $permissions = Permission::select('id', 'name')->get();
            return datatables()->of($permissions)
                ->addColumn('aksi', function ($permission) {
                    if (!auth()->user()->can('permissions.delete')) {
                        return '<span class="text-muted">No Access</span>';
                    }
                    $button = '<button class="btn btn-danger btn-sm hapus-permission" data-id="' . $permission->id . '" name="edit"><i class="ri-delete-bin-6-line"></i></button>';
                    return $button;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        return view('permissions.index');
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('permissions.update')) {
            abort(403, 'Anda tidak punya akses untuk mengedit permission');
        }
        $permission = Permission::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $permission->update(['name' => $request->name]);

        return response()->json(['message' => 'Permission berhasil diperbarui']);
    }


    public function destroy(Permission $permission)
    {
        $permission->delete();
        return response()->json(['message' => 'Permission deleted']);
    }

    /**
     * Show the form for creating a new resour
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('permissions.create')) {
            abort(403, 'Anda tidak punya akses untuk membuat permission');
        }

        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name, 'guard_name' => 'web']);
        return redirect()->route('permissions.index')->with('success', 'Permission created.');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'modules' => 'required|string'
        ]);

        $modules = explode(',', str_replace(' ', '', $request->modules));
        $actions = ['create', 'read', 'update', 'delete'];
        $created = [];
        $duplicate = [];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $permName = strtolower("{$module}.{$action}");
                $existing = Permission::where('name', $permName)->first();

                if ($existing) {
                    $duplicate[] = $existing->name;

                    // Hapus jika request hapus_duplikat = true
                    if ($request->boolean('hapus_duplikat')) {
                        $existing->delete();
                    }
                } else {
                    $permission = Permission::create([
                        'name' => $permName,
                        'guard_name' => 'web'
                    ]);
                    $created[] = $permission->name;
                }
            }
        }

        return response()->json([
            'message' => 'Generate selesai',
            'created' => $created,
            'duplicate' => $duplicate,
        ]);
    }
}
