<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class DestroyRoleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Role $role)
    {
        Gate::authorize('delete', $role);

        if ($role->users()->exists()) {
            return redirect()
                ->route('roles.index')
                ->with('error', 'Role tidak bisa dihapus karena masih memiliki user');
        }
        
        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('success', 'Role berhasil dihapus.');
    }
}
