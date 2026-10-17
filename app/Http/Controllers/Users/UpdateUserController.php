<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr; 
use Illuminate\Support\Facades\DB;

class UpdateUserController extends Controller
{
    public function __invoke(UpdateUserRequest $request, User $user): RedirectResponse {
        $data = $request->validated();
        $role = $data['role'] ?? null;  

        $data = Arr::except($data, ['role', 'password_confirmation']);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        if ($request->user()->is($user)) {
            unset($data['is_active']);
            $role = null;
        }

        $losingAdmin = $user->hasRole('Super Admin') && (
            ($role != null && $role !== 'Super Admin')
            || (array_key_exists('is_active', $data) && ! $data['is_active'])
        );

        if ($losingAdmin && User::role('Super Admin')->where('is_active, true')->count() <= 1) {
            return back()->withErrors([
                'role' => 'Minimal harus ada satu Super Admin aktif.',
            ]);
        }

        DB::transaction(function () use ($user, $data, $role) {
            $user->update($data);

            if ($role !=  null) {
                $user->syncRoles($role);
            }
        });

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil diperbarui,');
    }
}
