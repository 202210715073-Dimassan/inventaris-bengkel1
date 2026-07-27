<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Supplier;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->check() && auth()->user()->isOwner()) {
            return redirect()->route('dashboard')->with('error', 'Halaman Master Barang khusus untuk Admin operasional.');
        }

        $query = Product::with('supplier');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products  = $query->paginate(10);
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.index', compact('products', 'suppliers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'            => 'nullable|string|max:50|unique:products,code',
            'name'            => 'required|string|max:255',
            'category'        => 'nullable|string|max:100',
            'unit'            => 'required|string|max:50',
            'large_unit'      => 'nullable|string|max:50',
            'unit_conversion' => 'nullable|integer|min:1',
            'supplier_id'     => 'required|exists:suppliers,id',
            'stock'           => 'required|integer|min:0',
            'lead_time'       => 'required|integer|min:0',
            'max_lead_time'   => 'required|integer|min:0',
        ]);

        if (empty($data['large_unit'])) {
            $data['large_unit'] = null;
            $data['unit_conversion'] = 1;
        } else {
            $data['unit_conversion'] = $data['unit_conversion'] ?? 1;
        }

        // ss_value dan rop_value dimulai dari 0 — dihitung otomatis saat ada transaksi keluar
        $data['ss_value']  = 0;
        $data['rop_value'] = 0;

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validate([
            'code'            => 'nullable|string|max:50|unique:products,code,' . $product->id,
            'name'            => 'required|string|max:255',
            'category'        => 'nullable|string|max:100',
            'unit'            => 'required|string|max:50',
            'large_unit'      => 'nullable|string|max:50',
            'unit_conversion' => 'nullable|integer|min:1',
            'supplier_id'     => 'required|exists:suppliers,id',
            'stock'           => 'required|integer|min:0',
            'lead_time'       => 'required|integer|min:0',
            'max_lead_time'   => 'required|integer|min:0',
        ]);

        if (empty($data['large_unit'])) {
            $data['large_unit'] = null;
            $data['unit_conversion'] = 1;
        } else {
            $data['unit_conversion'] = $data['unit_conversion'] ?? 1;
        }

        $product->update($data);

        // Jika lead_time berubah, hitung ulang SS & ROP
        $product->recalculateSSandROP();
        $product->save();

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
