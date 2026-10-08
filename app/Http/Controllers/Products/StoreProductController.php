<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\StoreProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class StoreProductController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $data = $request->validated();

        // TODO: kalau ada file gambar yang dikirim, simpan ke disk
        // 'public' dalam folder 'products', lalu masukkan PATH hasilnya
        // (bukan file object-nya) ke $data['image'].
        //
        // Pertanyaan pemandu: method apa di objek UploadedFile
        // (hasil dari $request->file('image')) yang langsung menyimpan
        // file ke disk tertentu DAN mengembalikan path-nya sekaligus —
        // tanpa kamu perlu generate nama file manual? Cek dokumentasi
        // Laravel bagian "File Storage > Storing Uploaded Files".
        if ($request->hasFile('image')) {
            $data['image'] = '???';
        }

        Product::create($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }
}
