<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class UpdateProductController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // TODO: dua langkah di sini, URUTANNYA PENTING:
            // 1. Hapus file gambar LAMA dari disk (kalau ada), supaya
            //    tidak ada file "sampah" menumpuk di storage tiap kali
            //    produk diedit dengan gambar baru.
            // 2. Simpan file BARU, isi $data['image'] dengan path barunya.
            //
            // Pertanyaan pemandu: kalau langkah 1 dilakukan SETELAH
            // langkah 2 (bukan sebelum), dan ternyata nama file baru
            // kebetulan sama dengan nama file lama (jarang tapi bisa
            // terjadi), apa risikonya? Ini salah satu alasan urutan
            // hapus-dulu-baru-simpan lebih aman daripada sebaliknya.
        }

        // TODO: kalau kamu memutuskan menambahkan field 'remove_image'
        // dari pembahasan Request sebelumnya, ini tempatnya logic itu
        // dieksekusi — hapus file dari disk DAN set $data['image'] = null,
        // tapi HANYA kalau user tidak sekaligus upload gambar baru.

        $product->update($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }
}
