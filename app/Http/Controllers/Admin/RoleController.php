<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateRoleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::withCount('permissions')->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function edit(Role $role): View
    {
        $role->load('permissions');
        $permissions = Permission::all();

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }
}
