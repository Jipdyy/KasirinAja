<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class IndexRoleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        Gate::authorize('viewAny', Role::class);

        $roles = Role::query()
            ->withCount('permissions')
            ->latest()
            ->paginate(10);

        $permissions = Permission::query()
            ->orderBy('name')
            ->get()
            ->groupBy(fn ($permission) => Str::before($permission->name, '.'));

        return view('roles.index', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }
}
