<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pos\StoreTransactionRequest;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StoreTransactionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreTransactionRequest $request, CartService $cart): RedirectResponse
    {
        $validated = $request->validated();

        if ($cart->lines()->isEmpty()) {
            return back()->withErrors(['cart' => 'Keranjang masih kosong.']);
        }

        $transaction = DB::transaction(function () use ($validated, $cart, $request) {
            $raw = $cart->raw();
            $products = Product::whereIn('id', array_keys($raw))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');
            
            foreach($raw as $id => $qty) {
                $product = $products->get($id);

                if (! $product || ! $product->isSellAble() || $product->stock < $qty) {
                    $name = $product->name ?? 'Produk';

                    throw ValidationException::withMessages([
                        'cart' => "{$name} sudah tidak tersedia atau stoknya kurang.",
                    ]);
                }
            }
            
            $totalItems = array_sum($raw);
            $totalAmount = 0;
            foreach ($raw as $id => $qty) {
                $totalAmount += $products[$id]->price * $qty;
            }

            if ($validated['payment_method'] === Transaction::PAYMENT_TUNAI) {
                $cash = (int) $validated['cash_amount'];

                if ($cash < $totalAmount) {
                    throw ValidationException::withMessages([
                        'cash_amount' => 'Uang tunai kurang dari total belanja.',
                    ]);
                }

                $change = $cash - $totalAmount;
            } else {
                $cash = null;
                $change = 0;
            }

            $prefix = 'INV-' . now()->format('Ymd') . '-';
            $last = Transaction::where('invoice_code', 'like', $prefix . '%')
                ->orderByDesc('invoice_code')
                ->lockForUpdate()
                ->value('invoice_code');
            $next = $last ? ((int) substr($last, -4)) +1 : 1;
            $invoiceCode = $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
            
            $transaction = Transaction::create([
                'invoice_code'   => $invoiceCode,
                'user_id'        => $request->user()->id,
                'payment_method' => $validated['payment_method'],
                'total_items'    => $totalItems,
                'total_amount'   => $totalAmount,
                'cash_amount'    => $cash,
                'change_amount'  => $change,
                'status'         => 'completed',
            ]);
            
            foreach($raw as $id => $qty) {
                $product = $products[$id];

                $transaction->items()->create([
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'price'        => $product->price,
                    'qty'          => $qty,
                    'subtotal'     => $product->price * $qty,
                ]);

                $product->decrement('stock', $qty);
            }
            
            return $transaction;
        });

        $cart->clear();

        return redirect()
            ->route('pos.index')
            ->with('success', "Transaksi {$transaction->invoice_code} berhasil.");
    }
}
