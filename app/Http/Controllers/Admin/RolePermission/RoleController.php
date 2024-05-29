<?php

namespace App\Http\Controllers\Admin\RolePermission;

use App\Http\Controllers\Controller;
use App\Trait\Admin\FormHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use FormHelper;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check Authorize
        Gate::authorize('role.list');

        // All rolex except superadmin
        $roles = Role::where('name', '!=', 'superadmin')->get();

        return view('admin.role-permission.role.index', [
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check Authorize
        Gate::authorize('role.create');

        return view('admin.role-permission.role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check Authorize
        Gate::authorize('role.create');

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:roles,name'
            ],
        ]);

        // $validated = $request->validate();
        $role = Role::create($validated);

        session()->flash('success', 'Role has been created successfully');
        
        return $this->saveAndRedirect($request, 'roles-permissions.roles', $role->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return redirect()->route('admin.roles-permissions.roles.edit', $role->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        // Check Authorize
        Gate::authorize('role.update');

        // Restricted to edit super admin role
        if($role->name === 'superadmin'){
            session()->flash('error', 'You cannot edit the superadmin role');
            return redirect()->route('admin.roles-permissions.roles.index');
        }
        
        $permissions = Permission::all();

        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_has_permissions.role_id', $role->id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('admin.role-permission.role.edit', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        // Check Authorize
        Gate::authorize('role.update');

        // Restricted to edit super admin role
        if($role->name === 'superadmin'){
            session()->flash('error', 'You cannot edit the superadmin role');
            return redirect()->route('admin.roles-permissions.roles.index');
        }

        if ($request->has('syncPermissions')) {
            $request->validate([
                'permissions' => 'required',
            ]);
            if ($role) {
                $role->syncPermissions($request->permissions);
                session()->flash('success', 'Permission has been updated successfully');
                return back();
            }
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:roles,name,' . $role->id,
            ],
        ]);

        // $validated = $request->validate();
        $role->update($validated);

        session()->flash('success', 'Permission has been updated successfully');

        return $this->saveAndRedirect($request, 'roles-permissions.roles', $role->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Check Authorize
        Gate::authorize('role.delete');

        // Restricted to edit super admin role
        if($role->name === 'superadmin'){
            session()->flash('error', 'You cannot edit the superadmin role');
            return redirect()->route('admin.roles-permissions.roles.index');
        }
        
        if ($role) {
            $role->delete();
            session()->flash('success', 'Role has been deleted successfully');
            return redirect()->route('admin.roles-permissions.roles.index');
        }

        session()->flash('error', 'Role not found');
        return redirect()->route('admin.roles-permissions.roles.index');
    }
}
