<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($perm) {
            return explode('.', $perm->name)[0] ?? 'general';
        });

        return Inertia::render('Admin/Settings/Roles', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        try {
            $role = Role::create(['name' => $validated['name']]);

            if (!empty($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            }

            activity()
                ->performedOn($role)
                ->causedBy(auth()->user())
                ->log('Role created');

            return redirect()->back()->with('success', 'Role created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        try {
            if ($role->name === 'Super Admin') {
                return redirect()->back()->with('error', 'Super Admin role cannot be modified');
            }

            $role->update(['name' => $validated['name']]);
            $role->syncPermissions($validated['permissions'] ?? []);

            activity()
                ->performedOn($role)
                ->causedBy(auth()->user())
                ->log('Role updated');

            return redirect()->back()->with('success', 'Role updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Role $role)
    {
        try {
            if ($role->name === 'Super Admin') {
                return redirect()->back()->with('error', 'Super Admin role cannot be deleted');
            }

            if ($role->users()->count() > 0) {
                return redirect()->back()->with('error', 'Cannot delete role with assigned users');
            }

            $role->delete();

            activity()->causedBy(auth()->user())->log('Role deleted');

            return redirect()->back()->with('success', 'Role deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function assignUser(Request $request, Role $role)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            $user = \App\Models\User::findOrFail($request->user_id);
            $user->assignRole($role);

            return redirect()->back()->with('success', "Role '{$role->name}' assigned to user");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function removeUser(Request $request, Role $role)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            $user = \App\Models\User::findOrFail($request->user_id);

            if ($role->name === 'Super Admin') {
                return redirect()->back()->with('error', 'Cannot remove Super Admin role');
            }

            $user->removeRole($role);

            return redirect()->back()->with('success', "Role '{$role->name}' removed from user");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
