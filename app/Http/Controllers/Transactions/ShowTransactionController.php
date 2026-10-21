<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ShowTransactionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Transaction $transaction)
    {
        Gate::authorize('view', $transaction);

        $transaction->load(['items', 'cashier']);

        return view('transactions.show', [
            'transaction' => $transaction,
        ]);
    }
}
