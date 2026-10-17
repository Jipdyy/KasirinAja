<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;


class ShowUserController extends Controller
{
    public function __invoke(User $user): View {
        Gate::authorize('view', $user);

        $user->load('roles');

        return view('users.show', [
            'user' => $user,
            'totalTransaction' => $user->transactions()->count(),
        ]);
    }
}
