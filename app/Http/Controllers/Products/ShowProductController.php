<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;

class ShowProductController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Product $product)
    {
        Gate::authorize('view', $product);

        $product->load('category');

        return view('products.show', compact('product'));
    }
}
