<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Container\Attributes\Scoped;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private const LOW_STOCK_THRESHOLD = 10;
    private const OPEN_HOUR = 6;
    private const CLOSE_HOUR = 18;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $canViewAll = $user->can('transactions.view');
        $today = now()->toDateString();

        $scoped = fn () => Transaction::query()
            ->where('status', 'completed')
            ->dateBetween($today, $today)
            ->when(! $canViewAll, fn ($q) => $q->ownedBy($user->id));

        $stats = [
            'revenue'      => (int) $scoped()->sum('total_amount'),
            'transactions' => $scoped()->count(),
            'items'        => (int) $scoped()->sum('total_items'),
        ];

        $topProducts = TransactionItem::query()
            ->join('transactions', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->where('transactions.status', 'completed')
            ->whereBetween('transactions.created_at', [$today . ' 00:00:00', $today . ' 23:59:59'])
            ->when(! $canViewAll, fn ($q) => $q->where('transactions.user_id', $user->id))
            ->selectRaw('transaction_items.product_id, MAX(transaction_items.product_name) as name, SUM(transaction_items.qty) as qty')
            ->groupBy('transaction_items.product_id')
            ->orderByDesc('qty')
            ->limit(5)
            ->get();

        $lowStock = $user->can('products.view')
            ? Product::query()
                ->where('is_active', true)
                ->where('stock', '<', self::LOW_STOCK_THRESHOLD)
                ->orderBy('stock')
                ->limit(5)
                ->get()
            : collect();

        $recent = $scoped()
            ->with('cashier')
            ->latest()
            ->limit(8)
            ->get();

        return view('dashboard.index', [
            'stats'       => $stats,
            // 'hourly'      => $hourly,
            'topProducts' => $topProducts,
            'lowStock'    => $lowStock,
            'recent'      => $recent,
            'canViewAll'  => $canViewAll,
            'threshold'   => self::LOW_STOCK_THRESHOLD,
        ]);
    }
}
