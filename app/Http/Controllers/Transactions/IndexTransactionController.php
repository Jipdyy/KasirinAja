<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class IndexTransactionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request) : View
    {
        Gate::authorize('viewAny', Transaction::class);

        $filters = $request->validate([
            'search'  => ['nullable', 'string', 'max:50'],
            'cashier' => ['nullable', 'string', 'max:100'],
            'start'   => ['nullable', 'date'],
            'end'     => ['nullable', 'date', 'after_or_equal:start'],
            'payment' => ['nullable', 'in:tunai,qris'],
        ]);

        $user = $request->user();
        $canViewAll = $user->can('transactions.view-all');

        $query = Transaction::query()->with('cashier');

        if (! $canViewAll) {
            $query->ownedBy($user->id);
        }

        $query->searchInvoice($filters['search'] ?? null);

        if ($canViewAll) {
            $query->byCashier($filters['cashier'] ?? null);
        }

        if (! empty($filters['payment'])) {
            $query->where('payment_method', $filters['payment']);
        }

        $start = $filters['start'] ?? null;
        $end = $filters['end'] ?? null;

        if ($start || $end) {
            $query->dateBetween($start ?? '1970-01-01', $end ?? now()->toDateString());
        }

        $summary = [
            'count' => (clone $query)->count(),
            'sum'   => (int) (clone $query)->sum('total_amount'),
        ];

        $transactions = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('transactions.index', [
            'transactions' => $transactions,
            'filters'      => $filters,
            'canViewAll'   => $canViewAll,
            'summary'      => $summary,
        ]);
    }
}
