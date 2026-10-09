<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DestroyProductController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Product $product)
    {
        Gate::authorize('delete', $product);

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dinonaktifkan.');
    }
}
