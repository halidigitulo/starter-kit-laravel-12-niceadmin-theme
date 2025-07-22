<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class UserController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:users.create|users.read|users.update|users.delete'],['only'=>['index']]);
        // $this->middleware(['permission:users.create'], ['only' => ['storeOrUpdate']]);
        // $this->middleware(['permission:users.read'], ['only' => ['index']]);
        // $this->middleware(['permission:users.update'], ['only' => ['edit','storeOrUpdate']]);
        // $this->middleware('permission:users.update')->only('edit');
        // $this->middleware(['permission:users.delete'], ['only' => ['destroy']]);
    }

    public function index()
    {
        // Load all users with their roles
        if (request()->ajax()) {
            $users = User::with(['role'])->select('users.*');
            return datatables()->of($users)
                ->addColumn('role', function ($role) {
                    return optional($role->role)->name ?: 'N/A';
                })
                ->addColumn('aksi', function ($user) {
                    $button = '';

                    // $button .= '<button class="btn btn-warning btn-sm edit-user" data-id="' . $user->id . '" name="edit"><i class="ri-pencil-line"></i></button> ';

                    // Cek permission edit
                    if (Auth::user()->can('users.update')) {
                        $button .= '<button class="btn btn-warning btn-sm edit-user" data-id="' . $user->id . '" name="edit"><i class="ri-pencil-line"></i></button> ';
                    }

                    // Cek permission delete
                    if (Auth::user()->can('users.delete')) {
                        $button .= '<button class="btn btn-danger btn-sm hapus-user" data-id="' . $user->id . '" name="delete"><i class="ri-delete-bin-6-line"></i></button>';
                    }

                    return $button !== '' ? $button : '<span class="text-muted">No Access</span>';
                })

                ->rawColumns(['aksi'])
                ->make(true);
        }
        $role = Role::all();
        return view('users.index', compact('role') + [
            'canUpdateStatus' => Auth::user()->can('users.update')
        ]);
    }

    public function storeOrUpdate(Request $request, $id = null)
    {
        $rules = [
            'name' => 'nullable|string|max:255',
            'username' => $id
                ? 'nullable|string|max:255|unique:users,username,' . $id
                : 'required|string|max:255|unique:users,username',
            'password' => $id
                ? 'nullable|string|min:8'
                : 'required|string|min:8',
            'role_id' => 'nullable|integer|exists:roles,id',
            'permissions' => 'nullable|array', // opsional: jika kamu ingin custom permission per user
            'permissions.*' => 'string|exists:permissions,name',
            'is_active' => 'nullable|boolean',
            'avatar' => 'nullable|image|max:2048',
        ];

        $validatedData = $request->validate($rules);
        $user = $id ? User::findOrFail($id) : new User();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($id && $user->avatar && file_exists(public_path('uploads/users/' . $user->avatar))) {
                unlink(public_path('uploads/users/' . $user->avatar));
            }

            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('uploads/users/'), $avatarName);
            $validatedData['avatar'] = $avatarName;
        }

        // Handle password hashing
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        // Save user data
        $user->fill($validatedData)->save();

        // === ✅ Assign Role ===
        if ($request->filled('role_id')) {
            $guard = $user->guard_name ?? 'web'; // Default ke 'web' jika tidak diset
            $role = Role::where('id', $request->role_id)->where('guard_name', $guard)->firstOrFail();
            $user->syncRoles([$role->name]);
        }

        // === ✅ Assign Direct Permissions (optional) ===
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions); // array of permission names
        }

        return response()->json([
            'message' => $id ? 'User updated successfully!' : 'User created successfully!',
            'avatar' => $user->avatar ?? null
        ]);
    }

    public function edit($id)
    {
        if (!Auth::user()->can('users.update')) {
            abort(403, 'Anda tidak punya akses untuk mengedit user');
        }
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function destroy($id)
    {
        if (!Auth::user()->can('users.delete')) {
            abort(403, 'Anda tidak punya akses untuk menghapus user');
        }
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'User successfully deleted.',
        ]);
    }

    public function profile()
    {
        // Find the user by ID and ensure the authenticated user is authorized to update this profile
        $user = Auth::user(); // Fetch user by the provided ID

        // Check if the logged-in user is authorized to update the profile
        if (Auth::id() !== $user->id) {
            // Optionally, redirect or show an error if the user is trying to access another user's profile
            return redirect()->route('dashboard')->with('error', 'Unauthorized access to profile.');
        }

        return view('users.update', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $user->name = $request->name;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            $oldAvatarPath = public_path('uploads/users/' . $user->avatar);
            if ($user->avatar && file_exists($oldAvatarPath)) {
                unlink($oldAvatarPath);
            }

            $avatarName = time() . '.' . $request->avatar->extension();
            $request->avatar->move(public_path('uploads/users'), $avatarName);
            $user->avatar = $avatarName;
        }

        $user->save();

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'profile' => [
                'name' => $user->name,
                'avatar' => $user->avatar ? asset('uploads/users/' . $user->avatar) : null,
            ]
        ]);
    }
}
