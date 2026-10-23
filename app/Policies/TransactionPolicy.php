<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TransactionPolicy
{
    public function viewAny(User $user): bool {
        return $user->can('transactions.view-all')
            || $user->can('transactions.view-own');
    }

    public function view(User $user, Transaction $transaction): bool {
        if ($user->can('transactions.view-all')) {
            return true;
        }

        return $user->can('transactions.view-own')
            && $transaction->user_id === $user->id;
    }

    public function create(User $user): bool {
        return $user->can('pos.access');
    }
}
