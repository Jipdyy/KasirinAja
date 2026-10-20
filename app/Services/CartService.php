<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class CartService
{
    private const KEY = 'pos.cart';

    /** @return array<int,int> product_id => qty */
    public function raw(): array {
        return session(self::KEY, []);
    }

    public function add(Product $product, int $qty = 1): void {
        $this->set($product, ($this->raw()[$product->id] ?? 0) + $qty);
    }

    public function set(Product $product, int $qty): void {
        if ($qty <= 0) {
            $this->remove($product);
            return;
        }

        if ($qty > $product->stock) {
            throw ValidationException::withMessages([
                'qty' => "Stok {$product->name} tersisa {$product->stock}"
            ]);
        }

        $cart = $this->raw();
        $cart[$product->id] = $qty;
        session([self::KEY => $cart]);
    }

    public function remove(Product $product): void {
        $cart = $this->raw();
        unset($cart[$product->id]);
        session([self::KEY => $cart]);
    }

    public function clear(): void {
        session()->forget(self::KEY);
    }

    public function lines(): Collection {
        $raw = $this->raw();

        if ($raw === []) {
            return collect();
        }

        $products = Product::withTrashed()
            ->whereIn('id', array_keys($raw))
            ->get()
            ->keyBy('id');

        return collect($raw)
            ->map(function (int $qty, int $id) use ($products) {
                $product = $products->get($id);

                $issue = match (true) {
                    $product === null          => 'Produk tidak ditemukan',
                    $product->trashed()        => 'Produk sudah dihapus',
                    ! $product->is_available   => 'Produk tidak tersedia',
                    $product->stock < $qty     => "Stok tersisa {$product->stock}",
                    default                    => null,
                };

                return [
                    'product'  => $product,
                    'qty'      => $qty,
                    'subtotal' => $issue === null ? $product->price * $qty : 0,
                    'issue'    => $issue,
                ];
            })
            ->values();
    }


    public function hasIssues(): bool {
        return $this->lines()->contains(fn ($line) => $line['issue'] !== null);
    }

    public function totalItems(): int {
        return (int) $this->lines()->whereNull('issue')->sum('qty');
    }

    public function totalAmount(): int {
        return (int) $this->lines()->sum('subtotal');
    }
}