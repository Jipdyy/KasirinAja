<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ResetUserPasswordRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class ResetUserPasswordController extends Controller
{
    public function __invoke(ResetUserPasswordRequest $request, User $user): RedirectResponse{
        $user->update([
            'password' => $request->validated('password')
        ]);

        return back()->with('success', 'Password {$user->name} berhasil direset.');
    }
}
