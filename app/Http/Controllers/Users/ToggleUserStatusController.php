<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ToggleUserStatusController extends Controller
{
    public function __invoke(Request $request, User $user): RedirectResponse {
        if ($user->id === $request->user()->id) {
            return back()->with('error',  'Kamu tidak bisa menonaktifkan akunmu sendiri!');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        // TODO: pesan flash berbeda untuk "dinonaktifkan" dan "diaktifkan kembali".
        //       Bagaimana kamu menentukan pesannya setelah nilainya dibalik?
        $message = $user->is_active
            ? "Akun {$user->name} berhasil diaktifkan kembali."
            : "Akun {$user->name} berhasil dinonaktifkan.";

        return back()->with('success', $message);
    }
}
