<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class UpdateProductController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $oldImage = $product->image;

        unset($data['remove_image']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        } elseif ($request->boolean('remove_image')) {
            $data['image'] = null;
        } else {
            unset($data['image']);
        }

        $product->update($data);
        if (array_key_exists('image', $data) && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }
}
