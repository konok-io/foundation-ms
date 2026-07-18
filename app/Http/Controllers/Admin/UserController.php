<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('users.view');

        $query = User::with('roles');

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $data = [
            'title' => 'User Management',
            'page_title' => 'User Management',
            'users' => $query->paginate(15),
            'roles' => Role::all(),
        ];

        return view('admin.users.index', $data);
    }

    public function create()
    {
        $this->authorize('users.create');

        $data = [
            'title' => 'Create User',
            'page_title' => 'Create New User',
            'roles' => Role::all(),
        ];

        return view('admin.users.create', $data);
    }

    public function store(UserStoreRequest $request)
    {
        $this->authorize('users.create');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => $request->status ?? 'active',
        ]);

        if ($request->has('roles')) {
            $user->assignRole($request->roles);
        }

        Log::info('User created', ['user_id' => $user->id, 'created_by' => auth()->id()]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $this->authorize('users.view');

        $data = [
            'title' => 'User Details',
            'page_title' => 'User Details',
            'user' => $user->load('roles', 'permissions'),
            'activities' => \App\Models\ActivityLog::where('causer_id', $user->id)
                ->latest()
                ->take(20)
                ->get(),
        ];

        return view('admin.users.show', $data);
    }

    public function edit(User $user)
    {
        $this->authorize('users.edit');

        $data = [
            'title' => 'Edit User',
            'page_title' => 'Edit User',
            'user' => $user,
            'roles' => Role::all(),
            'user_roles' => $user->roles->pluck('id')->toArray(),
        ];

        return view('admin.users.edit', $data);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $this->authorize('users.edit');

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        if ($request->has('password') && $request->password) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        Log::info('User updated', ['user_id' => $user->id, 'updated_by' => auth()->id()]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->authorize('users.delete');

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account.');
        }

        Log::info('User deleted', ['user_id' => $user->id, 'deleted_by' => auth()->id()]);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function updateStatus(Request $request, User $user)
    {
        $this->authorize('users.edit');

        $request->validate([
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $user->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
