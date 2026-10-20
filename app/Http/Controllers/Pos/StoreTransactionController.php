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
            // TODO 1: kunci baris produk yang ada di cart supaya dua kasir
            //   tidak bisa menjual stok terakhir yang sama:
            //   Product::whereIn('id', ...)->lockForUpdate()->get()->keyBy('id')
            //   Kenapa lockForUpdate harus DI DALAM DB::transaction, bukan
            //   sebelumnya?
            $products = Product::whereIn('id', array_keys($raw))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');
            
            // TODO 2: untuk tiap item, cek ulang isSellable() dan stock >= qty
            //   dari data yang BARU dikunci. Kalau gagal, lempar
            //   ValidationException agar seluruh transaksi di-rollback.
            //   Kenapa cek di cart saja tidak cukup?
            foreach($raw as $id => $qty) {
                $product = $products->get($id);

                if (! $product || ! $product->isSellAble() || $product->stock < $qty) {
                    $name = $product->name ?? 'Produk';

                    throw ValidationException::withMessages([
                        'cart' => "{$name} sudah tidak tersedia atau stoknya kurang.",
                    ]);
                }
            }
            
            // TODO 3: hitung total_items dan total_amount dari harga di
            //   database (bukan dari request, bukan dari session).
            $totalItems = array_sum($raw);
            $totalAmount = 0;
            foreach ($raw as $id => $qty) {
                $totalAmount += $product[$id]->price * $qty;
            }
            
            // TODO 4: tunai -> cash_amount harus >= total_amount, lalu
            //   change_amount = cash_amount - total_amount.
            //   qris  -> cash_amount null, change_amount 0.
            if ($validated['payment_method'] === Transaction::PAYMENT_TUNAI) {
                $cash = (int) $validated('cash_amount');

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

            
            // TODO 5: buat invoice_code unik (maks 30 karakter), mis.
            //   INV-20261020-0001. Pikirkan: kalau dua kasir checkout di
            //   detik yang sama, bagaimana mencegah kode kembar? Pilihannya:
            //   nomor urut harian yang dihitung di dalam transaksi ini, atau
            //   tanggal + sufiks acak dengan retry saat unique gagal.
            $prefix = 'INV-' . now()->format('Ymd') . '-';
            $last = Transaction::where('invoice_code', 'like', $prefix, '%')
                ->orderByDesc('invoice_code')
                ->lockForUpdate()
                ->vallue('invoice_code');
            $next = $last ? ((int) substr($last, -4)) +1 : 1;
            $invoiceCode = $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
            
            // TODO 6: Transaction::create([...]) dengan user_id dari
            //   $request->user()->id dan status 'completed'.
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
            
            // TODO 7: untuk tiap item: $transaction->items()->create([...])
            //   dengan snapshot product_name, price, qty, subtotal,
            //   lalu kurangi stok (decrement) produk terkait.
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
