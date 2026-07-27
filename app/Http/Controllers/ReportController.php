<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        $urgentProducts = Product::whereColumn('stock', '<=', 'rop_value')->get();
        return view('reports.restock', compact('urgentProducts'));
    }

    public function exportPdf()
    {
        $urgentProducts = Product::whereColumn('stock', '<=', 'rop_value')->get();
        $pdf = Pdf::loadView('reports.pdf', compact('urgentProducts'));
        
        return $pdf->download('laporan-restock-mogerzz.pdf');
    }

    public function exportExcel()
    {
        $urgentProducts = Product::whereColumn('stock', '<=', 'rop_value')->get();
        $filename = "laporan-restock-mogerzz.csv";
        $handle = fopen('php://output', 'w');

        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        // Add CSV headers
        fputcsv($handle, ['Kode Barang', 'Nama Barang', 'Stok Saat Ini', 'Batas ROP', 'Lead Time (Hari)']);

        // Add data
        foreach ($urgentProducts as $product) {
            fputcsv($handle, [
                $product->code ?? ('PRD-' . str_pad($product->id, 3, '0', STR_PAD_LEFT)),
                $product->name,
                $product->stock,
                round($product->rop_value),
                $product->lead_time
            ]);
        }

        fclose($handle);
        exit;
    }
}
