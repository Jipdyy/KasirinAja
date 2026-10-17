<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class IndexUserController extends Controller
{
    public function __invoke(Request $request): View {
        Gate::authorize('viewAny', User::class);

        $users = User::with('roles')
            ->search($request->search)
            ->when($request->role, fn ($query, $role) => $query->role($role))
            ->when($request->status, fn ($query, $status) =>
                   $query->where('is_active', $status === 'active'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('users.index', [
            'users' => $users,
            'roles' => Role::orderBy('name')->get(), 
        ]);
    }
}
