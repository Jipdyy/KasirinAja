<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ShowProductController extends Controller
{
    public function __invoke(Request $request, Product $product): View
    {
        Gate::authorize('view', $product);

        $product->load('category');

        $canSeeSales = $request->user()->can('transactions.view');

        $sales = null;
        $salesSummary = null;

        if ($canSeeSales) {
            $salesQuery = $product->transactionItems()
                ->whereHas('transaction', fn ($q) => $q->where('status', 'completed'));

            $row = (clone $salesQuery)
                ->selectRaw('COALESCE(SUM(qty), 0) as total_qty, COALESCE(SUM(subtotal), 0) as total_sales, COUNT(*) as total_trx')
                ->first();

            $salesSummary = (object) [
                'qty'   => (int) $row->total_qty,
                'total' => (int) $row->total_sales,
                'trx'   => (int) $row->total_trx,
            ];

            $sales = $salesQuery
                ->with('transaction.cashier')
                ->latest('id')
                ->paginate(10, ['*'], 'sales_page')
                ->withQueryString();
        }

        return view('products.show', [
            'product'      => $product,
            'canSeeSales'  => $canSeeSales,
            'sales'        => $sales,
            'salesSummary' => $salesSummary,
        ]);
    }
}