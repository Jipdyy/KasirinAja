<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\StoreRoleRequest;
use Spatie\Permission\Models\Role;

class StoreRoleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreRoleRequest $request)
    {
        $validated = $request->validated();

        $role = Role::create(['name' => $validated['name']]);

        $role->syncPermissions($validated['permissions'] ?? []);
        
        return redirect()
            ->route('roles.index')
            ->with('success', 'Role berhasil dibuat.');
    }
}
