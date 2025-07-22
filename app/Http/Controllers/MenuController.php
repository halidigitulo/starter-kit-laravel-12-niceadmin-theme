<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Menu::with('parent')->select('*')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($user) {
                    if (!auth()->user()->can('menus.update') && !auth()->user()->can('menus.delete')) {
                        return '<span class="text-muted">No Access</span>';
                    }
                    $editButton = '<button class="btn btn-warning btn-sm edit-user" data-id="' . $user->id . '" name="edit"><i class="ri-pencil-line"></i></button>';
                    $spasi = ' ';
                    if ($user->is_protected) {
                        return $editButton;
                    }
                    $deleteButton = '<button class="btn btn-danger btn-sm hapus-user" data-id="' . $user->id . '" name="edit"><i class="ri-delete-bin-6-line"></i></button>';
                    return $editButton . ' ' . $spasi . ' ' . $deleteButton;
                })
                ->addColumn('protected', function ($row) {
                    return $row->is_protected ? '<span class="badge bg-danger">Yes</span>' : '<span class="badge bg-secondary">No</span>';
                })
                ->editColumn('parent_name', function ($row) {
                    return $row->parent->name ?? '-';
                })
                ->rawColumns(['protected', 'aksi'])
                ->make(true);
        }
        $menus = Menu::whereNull('parent_id')->get();

        return view('menus.index', compact('menus'));
    }

    public function order()
    {
        $menus = Menu::with(['children' => function ($q) {
            $q->orderBy('sort_order');
        }])
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        return view('menus.order', compact('menus'));
    }

    public function reorder(Request $request)
    {
        foreach ($request->order as $item) {
            Menu::where('id', $item['id'])->update([
                'sort_order' => $item['sort_order'],
                'parent_id' => $item['parent_id']
            ]);
        }

        return response()->json(['message' => 'Urutan berhasil disimpan']);
    }


    public function store(Request $request)
    {
        Menu::create($request->only('name', 'url', 'icon', 'parent_id', 'permission_name'));
        return response()->json(['message' => 'Menu created']);
    }

    public function show(Menu $menu)
    {
        return response()->json($menu);
    }

    public function update(Request $request, Menu $menu)
    {
        $menu->update($request->only('name', 'url', 'icon', 'parent_id', 'permission_name'));
        return response()->json(['message' => 'Menu updated successfully']);
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response()->json(['message' => 'Menu deleted successfully!']);
    }
}
