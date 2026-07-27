<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->isOwner()) {
            return redirect()->route('dashboard')->with('error', 'Halaman Transaksi khusus untuk Admin operasional.');
        }

        $transactions = Transaction::with('product.supplier')->latest()->paginate(10);
        $products     = Product::with('supplier')->orderBy('name')->get();

        return view('transactions.index', compact('transactions', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'       => 'required|exists:products,id',
            'type'             => 'required|in:in,out',
            'quantity'         => 'required|integer|min:1',
            'transaction_unit' => 'nullable|string',
            'transaction_date' => 'required|date',
            'notes'            => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($data['product_id']);

        // Tentukan satuan transaksi default (satuan terkecil) jika kosong
        $txUnit = $data['transaction_unit'] ?? $product->unit;
        
        // Simpan kuantitas original yang diinput
        $originalQty = (int)$data['quantity'];
        
        // Hitung kuantitas dalam satuan dasar (terkecil)
        $baseQty = $originalQty;
        if ($product->large_unit && $txUnit === $product->large_unit) {
            $baseQty = $originalQty * $product->unit_conversion;
        }

        if ($data['type'] === 'out' && $product->stock < $baseQty) {
            return redirect()->back()->with('error', "Stok tidak mencukupi untuk transaksi keluar. Stok saat ini: {$product->formatted_stock}.");
        }

        // Siapkan data untuk disimpan ke database
        $data['quantity']          = $baseQty;
        $data['transaction_unit']  = $txUnit;
        $data['original_quantity'] = $originalQty;

        DB::transaction(function () use ($data, $product, $baseQty) {
            Transaction::create($data);

            // Update stok produk
            if ($data['type'] === 'in') {
                $product->stock += $baseQty;
            } else {
                $product->stock -= $baseQty;
            }

            // Hitung ulang SS & ROP menggunakan method di model
            $product->recalculateSSandROP();
            $product->save();
        });

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil disimpan.');
    }
}
