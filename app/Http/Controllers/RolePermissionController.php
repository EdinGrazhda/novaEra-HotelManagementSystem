<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of roles and permissions.
     */
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $users = User::with('roles')->get();
        
        return view('rolesPermissions.index', compact('roles', 'permissions', 'users'));
    }
    
    /**
     * Show the form for creating a new role.
     */
    public function createRole()
    {
        $permissions = Permission::all();
        return view('rolesPermissions.create-role', compact('permissions'));
    }
    
    /**
     * Store a newly created role in storage.
     */    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);
        
        DB::beginTransaction();
        try {
            $role = Role::create(['name' => $request->name]);
            
            if ($request->has('permissions')) {
                // Get permission objects by IDs
                $permissionIds = $request->permissions;
                $permissions = Permission::whereIn('id', $permissionIds)->get();
                $role->syncPermissions($permissions);
            }
            
            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating role: ' . $e->getMessage());
        }
    }
    
    /**
     * Show the form for editing the specified role.
     */
    public function editRole(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('rolesPermissions.edit-role', compact('role', 'permissions', 'rolePermissions'));
    }
    
    /**
     * Update the specified role in storage.
     */    public function updateRole(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
        ]);
        
        DB::beginTransaction();
        try {
            $role->update(['name' => $request->name]);
            
            // Sync permissions using permission objects
            $permissionIds = $request->input('permissions', []);
            $permissions = empty($permissionIds) 
                ? [] 
                : Permission::whereIn('id', $permissionIds)->get();
                
            $role->syncPermissions($permissions);
            
            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating role: ' . $e->getMessage());
        }
    }
    
    /**
     * Remove the specified role from storage.
     */
    public function destroyRole(Role $role)
    {
        if ($role->name === 'admin') {
            return back()->with('error', 'Cannot delete the admin role.');
        }
        
        DB::beginTransaction();
        try {
            $role->delete();
            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting role: ' . $e->getMessage());
        }
    }
    
    /**
     * Show the form for assigning roles to a user.
     */
    public function editUserRoles(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        
        return view('rolesPermissions.edit-user-roles', compact('user', 'roles', 'userRoles'));
    }
    
    /**
     * Update the roles assigned to a user.
     */    public function updateUserRoles(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'nullable|array',
        ]);
        
        DB::beginTransaction();
        try {
            // We get role IDs from the form but syncRoles needs role names
            // So we need to get the role names
            $roleIds = $request->input('roles', []);
            if (!empty($roleIds)) {
                $roleNames = Role::whereIn('id', $roleIds)->pluck('name')->toArray();
                $user->syncRoles($roleNames);
            } else {
                $user->syncRoles([]);
            }
            
            DB::commit();
            return redirect()->route('roles.index')->with('success', 'User roles updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating user roles: ' . $e->getMessage());
        }
    }
    
    /**
     * Show form to create a new permission (for advanced users).
     */
    public function createPermission()
    {
        return view('rolesPermissions.create-permission');
    }
    
    /**
     * Store a newly created permission.
     */
    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);
        
        DB::beginTransaction();
        try {
            Permission::create(['name' => $request->name]);
            
            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Permission created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating permission: ' . $e->getMessage());
        }
    }
}
