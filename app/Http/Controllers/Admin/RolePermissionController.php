<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Inertia\Inertia;
use App\Models\Permission;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionController extends Controller
{
    /**
     * List roles with permissions.
     */
    public function index(Request $request)
    {
        return Inertia::render('admin/RolesPermission', [
            'roles' => (fn () => Cache::remember(
                'all_roles_with_allowed_permissions',
                CarbonInterval::day(),
                fn () => Role::query()->with(['permissions'])->get()
            )),
            'permissions' => (fn () => Cache::remember(
                'all_permissions',
                CarbonInterval::day(),
                fn () => Permission::all()
            )),
        ]);
    }

    public function update(Role $role, Request $request)
    {
        $permissions = $request->validate([
            'permissions'   => 'array',
            'permissions.*' => 'integer|exists:permissions,id',
        ])['permissions'];

        $role->syncPermissions($permissions);

        Cache::forget('all_roles_with_allowed_permissions');
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        return back()
            ->with('success', 'Role permissions updated successfully.');
    }
}
