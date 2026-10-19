<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\UpdateRoleRequest;
use Spatie\Permission\Models\Role;

class UpdateRoleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateRoleRequest $request, Role $role)
    {
        $validated = $request->validated();

        $role->update(['name' => $validated['name']]);

        $role->syncPermissions($validated['permissions'] ?? []);


        return redirect()
            ->route('roles.index')
            ->with('success', 'Role berhasil diperbarui.');
    }
}
