<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class StoreUserController extends Controller
{
    public function __invoke(StoreUserRequest $request): RedirectResponse {
         $data = $request->validated();

         DB::transaction(function () use ($data){
            $user = User::create(Arr::except($data, ['role, password_confirmation']));
            $user->syncRoles($data['role']);
         });

         return redirect()
            ->route('user.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }
}
