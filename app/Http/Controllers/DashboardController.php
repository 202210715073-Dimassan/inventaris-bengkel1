<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');

        // FIX: Gunakan transaction_date bukan created_at
        $transactionsToday = Transaction::whereDate('transaction_date', today())->count();
        $inToday           = Transaction::whereDate('transaction_date', today())->where('type', 'in')->sum('quantity');
        $outToday          = Transaction::whereDate('transaction_date', today())->where('type', 'out')->sum('quantity');

        // Produk yang butuh restock (stok <= ROP), sertakan supplier
        $urgentProducts = Product::with('supplier')
            ->whereColumn('stock', '<=', 'rop_value')
            ->take(5)
            ->get();

        $lowStockCount = Product::whereColumn('stock', '<=', 'rop_value')->count();

        // Ambil semua produk untuk menampilkan stok barang di dashboard
        $products = Product::with('supplier')->orderBy('stock', 'asc')->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalStock',
            'transactionsToday',
            'inToday',
            'outToday',
            'urgentProducts',
            'lowStockCount',
            'products'
        ));
    }
}
