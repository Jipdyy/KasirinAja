<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;

class DestroyProductController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Product $product)
    {
        $this->authorize('delete', $product);

        // INGAT dari pembahasan Policy: Produk TIDAK PERNAH ditolak
        // dihapus seperti Kategori. Selalu soft delete + nonaktifkan.
        $product->update(['is_available' => false]);
        $product->delete();

        // TODO pertanyaan pemandu: apakah file gambar di storage perlu
        // ikut dihapus di sini? Pikirkan — ini SOFT delete, bukan
        // permanen. Kalau file gambar langsung dihapus dari disk
        // sekarang, apa yang terjadi kalau suatu saat produk ini perlu
        // "dipulihkan" (restore dari soft delete)? Pertimbangkan
        // membiarkan file gambar tetap ada selama produknya masih
        // cuma soft-deleted, bukan dihapus permanen dari database.

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dinonaktifkan.');
    }
}
