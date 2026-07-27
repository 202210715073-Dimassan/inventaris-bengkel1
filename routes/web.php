<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::get('/reports/restock', [ReportController::class, 'index'])->name('reports.restock');
    Route::get('/reports/restock/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
    Route::get('/reports/restock/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
});

// Route Impor Data Riil Sementara (Menjalankan Migrasi & Seeder)
Route::get('/import-real-data', function() {
    try {
        // 1. Jalankan migrasi database baru secara otomatis
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrasiOutput = trim(\Illuminate\Support\Facades\Artisan::output());

        // 2. Jalankan seeder impor data
        $seeder = new \Database\Seeders\ImportRealDataSeeder();
        $seeder->run();

        return response()->json([
            'status'          => 'success',
            'message'         => 'Proses impor berhasil! Migrasi dijalankan, master barang didaftarkan, dan transaksi penjualan telah diimpor.',
            'total_barang'    => \App\Models\Product::count(),
            'total_transaksi' => \App\Models\Transaction::count(),
            'output_migrasi'  => $migrasiOutput ?: 'Tidak ada migrasi baru (sudah up-to-date)',
        ], 200, [], JSON_PRETTY_PRINT);
    } catch (\Exception $e) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Gagal mengimpor data: ' . $e->getMessage(),
            'file'    => $e->getFile() . ':' . $e->getLine(),
            'trace'   => collect(explode("\n", $e->getTraceAsString()))->take(15)->implode("\n"),
        ], 500, [], JSON_PRETTY_PRINT);
    }
});

// Route untuk mencetak tabel perbaikan manual
Route::get('/tabel-perbaikan', function() {
    $products = \App\Models\Product::orderBy('id')->get();
    
    $markdown = "| No | Kode | Nama Barang | Dmax × Lmax | Davg × Lavg | SS = (Dmax×Lmax) − (Davg×Lavg) | ROP = (Davg×Lavg) + SS |\n";
    $markdown .= "|---|---|---|---|---|---|---|\n";
    
    $no = 1;
    foreach ($products as $p) {
        $dmax = $p->max_usage;
        $davg = round($p->average_usage, 2);
        $lmax = $p->max_lead_time;
        $lavg = $p->lead_time;
        
        $dlmax = $dmax * $lmax;
        
        // Agar presisi sama dengan Product.php
        $raw_avg = $p->average_usage;
        $dlavg = round($raw_avg * $lavg, 2);
        
        $ss = $dlmax - $dlavg;
        $ss_ceil = ceil($ss);
        
        $rop = $dlavg + $ss; // Gunakan nilai SS yang asli (sebelum ceil) sesuai manual
        $rop_ceil = ceil($rop);
        
        // Memformat string
        $dmax_lmax_str = "{$dmax} × {$lmax} = {$dlmax}";
        $davg_lavg_str = round($raw_avg, 2) . " × {$lavg} = {$dlavg}";
        $ss_str = "{$dlmax} − {$dlavg} = {$ss} (→ {$ss_ceil})";
        $rop_str = "{$dlavg} + {$ss} = {$rop} (→ {$rop_ceil})";
        
        $markdown .= "| {$no} | {$p->code} | {$p->name} | {$dmax_lmax_str} | {$davg_lavg_str} | {$ss_str} | {$rop_str} |\n";
        $no++;
    }
    
    return response($markdown)->header('Content-Type', 'text/plain');
});
