<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class ShowRoleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Role $role)
    {
        Gate::authorize('view', $role);

        $role->load('permissions');

        return response()->json([
            'id'            => $role->id,
            'name'          => $role->name,
            'permissions'   => $role->permissions->pluck('name')
        ]);
    }
}
