<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SalesReportService
{
    private function base(string $start, string $end): Builder
    {
        return Transaction::query()
            ->where('status', 'completed')
            ->dateBetween($start, $end);
    }

    private function itemsBase(string $start, string $end): Builder
    {
        return TransactionItem::query()
            ->join('transactions', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->where('transactions.status', 'completed')
            ->whereBetween('transactions.created_at', [
                Carbon::parse($start)->startOfDay(),
                Carbon::parse($end)->endOfDay(),
            ]);
    }

    public function summary(string $start, string $end): array
    {
        $row = $this->base($start, $end)
            ->selectRaw('COUNT(*) as trx, COALESCE(SUM(total_amount), 0) as revenue, COALESCE(SUM(total_items), 0) as items')
            ->first();

        $trx     = (int) $row->trx;
        $revenue = (int) $row->revenue;

        return [
            'revenue'      => $revenue,
            'transactions' => $trx,
            'items'        => (int) $row->items,
            'average'      => $trx > 0 ? (int) round($revenue / $trx) : 0,
        ];
    }

    public function dailyTrend(string $start, string $end): array
    {
        return $this->dailyBreakdown($start, $end)
            ->pluck('revenue', 'date')
            ->all();
    }

    public function paymentRatio(string $start, string $end): array
    {
        $rows = $this->base($start, $end)
            ->selectRaw('payment_method, COUNT(*) as trx, SUM(total_amount) as total')
            ->groupBy('payment_method')
            ->get();

        $total = (int) $rows->sum('total');
        $noncash = (int) $rows->where('payment_method', '!=', Transaction::PAYMENT_TUNAI)->sum('total');

        return [
            'rows'            => $rows,
            'total'           => $total,
            'noncash_total'   => $noncash,
            'noncash_percent' => $total > 0 ? round($noncash / $total * 100, 1) : 0,
        ];
    }

    public function categoryComposition(string $start, string $end): Collection
    {
        $rows = $this->itemsBase($start, $end)
            ->join('products', 'products.id', '=', 'transaction_items.product_id')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->selectRaw('categories.id, categories.name, SUM(transaction_items.subtotal) as total')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->get();

        $grandTotal = (int) $rows->sum('total');

        return $rows->map(fn ($r) => [
            'id'      => $r->id,
            'name'    => $r->name ?? 'Tanpa kategori',
            'total'   => (int) $r->total,
            'percent' => $grandTotal > 0 ? round($r->total / $grandTotal * 100, 1) : 0.0,
        ]);
    }

    public function topProducts(string $start, string $end, int $limit = 10): Collection
    {
        return TransactionItem::query()
            ->join('transactions', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->where('transactions.status', 'completed')
            ->whereBetween('transactions.created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
            ->selectRaw('transaction_items.product_id, MAX(transaction_items.product_name) as product_label, SUM(transaction_items.qty) as total_qty, SUM(transaction_items.subtotal) as total_sales')
            ->groupBy('transaction_items.product_id')
            ->orderByDesc('total_qty')
            ->limit($limit)
            ->get()
            ->map(fn ($row) => (object) [
                'name'  => $row->product_label,
                'qty'   => (int) $row->total_qty,
                'total' => (int) $row->total_sales,
            ]);
    }

    public function dailyBreakdown(string $start, string $end): Collection
    {
        $rows = $this->base($start, $end)
            ->selectRaw('DATE(created_at) as day, COUNT(*) as trx, SUM(total_items) as items, SUM(total_amount) as revenue')
            ->selectRaw('SUM(CASE WHEN payment_method = ? THEN total_amount ELSE 0 END) as cash', [Transaction::PAYMENT_TUNAI])
            ->selectRaw('SUM(CASE WHEN payment_method <> ? THEN total_amount ELSE 0 END) as non_cash', [Transaction::PAYMENT_TUNAI])
            ->groupByRaw('DATE(created_at)')
            ->get()
            ->keyBy('day');

        $result = collect();

        foreach (CarbonPeriod::create($start, $end) as $date) {
            $key = $date->toDateString();
            $r   = $rows->get($key);

            $result->push([
                'date'         => $key,
                'transactions' => (int) ($r->trx ?? 0),
                'items'        => (int) ($r->items ?? 0),
                'revenue'      => (int) ($r->revenue ?? 0),
                'cash'         => (int) ($r->cash ?? 0),
                'non_cash'     => (int) ($r->non_cash ?? 0),
            ]);
        }

        return $result;
    }
}