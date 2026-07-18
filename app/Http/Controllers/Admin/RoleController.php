<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('roles.view');

        $query = Role::with('permissions');

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $data = [
            'title' => 'Role Management',
            'page_title' => 'Role Management',
            'roles' => $query->paginate(15),
        ];

        return view('admin.roles.index', $data);
    }

    public function create()
    {
        $this->authorize('roles.create');

        $data = [
            'title' => 'Create Role',
            'page_title' => 'Create New Role',
            'permissions' => Permission::all()->groupBy('group_name'),
        ];

        return view('admin.roles.create', $data);
    }

    public function store(RoleStoreRequest $request)
    {
        $this->authorize('roles.create');

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
                'description' => $request->description,
                'guard_name' => 'web',
            ]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            Log::info('Role created', ['role_id' => $role->id, 'created_by' => auth()->id()]);

            DB::commit();

            return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create role: ' . $e->getMessage());
        }
    }

    public function show(Role $role)
    {
        $this->authorize('roles.view');

        $data = [
            'title' => 'Role Details',
            'page_title' => 'Role Details',
            'role' => $role->load('permissions', 'users'),
        ];

        return view('admin.roles.show', $data);
    }

    public function edit(Role $role)
    {
        $this->authorize('roles.edit');

        $data = [
            'title' => 'Edit Role',
            'page_title' => 'Edit Role',
            'role' => $role->load('permissions'),
            'permissions' => Permission::all()->groupBy('group_name'),
            'role_permissions' => $role->permissions->pluck('id')->toArray(),
        ];

        return view('admin.roles.edit', $data);
    }

    public function update(RoleUpdateRequest $request, Role $role)
    {
        $this->authorize('roles.edit');

        DB::beginTransaction();
        try {
            $role->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            } else {
                $role->syncPermissions([]);
            }

            Log::info('Role updated', ['role_id' => $role->id, 'updated_by' => auth()->id()]);

            DB::commit();

            return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update role: ' . $e->getMessage());
        }
    }

    public function destroy(Role $role)
    {
        $this->authorize('roles.delete');

        if ($role->name === 'Super Admin') {
            return redirect()->route('admin.roles.index')->with('error', 'Cannot delete Super Admin role.');
        }

        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')->with('error', 'Cannot delete role with assigned users.');
        }

        Log::info('Role deleted', ['role_id' => $role->id, 'deleted_by' => auth()->id()]);
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }

    public function getPermissions(Role $role)
    {
        return response()->json([
            'success' => true,
            'permissions' => $role->permissions->pluck('id'),
        ]);
    }
}
