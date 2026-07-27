<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ImportRealDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama agar database bersih dari produk & transaksi dummy sebelumnya
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Product::truncate();
        Transaction::truncate();
        Supplier::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // Seed default admin user
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@mogerzz.com'],
            [
                'name' => 'Admin Mo Gerzz',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Seed default owner user
        \App\Models\User::updateOrCreate(
            ['email' => 'owner@mogerzz.com'],
            [
                'name' => 'Owner Mo Gerzz',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'owner',
            ]
        );

        // 1. Buat Supplier Resmi berdasarkan data
        $suppliersMap = [];
        $supplierDataList = [
            'PT Piaggio Pratama' => ['phone' => '081299887766', 'email' => 'piaggio@supplier.com', 'address' => 'Jakarta'],
            'CV Abadi Karunia' => ['phone' => '081388776655', 'email' => 'abadi@supplier.com', 'address' => 'Jakarta'],
            'PT Mobility Indonesia' => ['phone' => '081577665544', 'email' => 'mobility@supplier.com', 'address' => 'Jakarta'],
        ];

        foreach ($supplierDataList as $sName => $sInfo) {
            $suppliersMap[$sName] = Supplier::create([
                'name' => $sName,
                'phone' => $sInfo['phone'],
                'email' => $sInfo['email'],
                'address' => $sInfo['address'],
            ]);
        }

        $defaultSupplier = reset($suppliersMap);

        $productsPath = database_path('data/products.csv');
        $transactionsPath = database_path('data/transactions.csv');
        $incomingPath = database_path('data/incoming_transactions.csv');

        if (!File::exists($productsPath) || !File::exists($transactionsPath)) {
            throw new \Exception("File CSV products.csv atau transactions.csv tidak ditemukan di folder database/data/.");
        }

        // Parse transactions.csv (Barang Keluar) terlebih dahulu untuk menghitung total penjualan per barang
        $transactionsData = [];
        $totalSoldMap = []; // code/name => total_qty

        if (($handle = fopen($transactionsPath, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ',');
            if ($header) {
                $header = array_map(fn($h) => trim($h, "\xEF\xBB\xBF\r\n\t "), $header);

                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    if (count($row) < count($header)) continue;
                    $data = array_combine($header, $row);
                    $transactionsData[] = $data;

                    $code = trim($data['kode_barang'] ?? '');
                    $name = trim($data['nama_barang'] ?? '');
                    $qty = (int)($data['quantity'] ?? 0);

                    $key = $code ?: $name;
                    if ($key) {
                        $totalSoldMap[$key] = ($totalSoldMap[$key] ?? 0) + $qty;
                    }
                }
            }
            fclose($handle);
        }

        // Parse products.csv dan daftarkan barang
        $productsMap = []; // code/name => Product model
        if (($handle = fopen($productsPath, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ',');
            if ($header) {
                $header = array_map(fn($h) => trim($h, "\xEF\xBB\xBF\r\n\t "), $header);

                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    if (count($row) < count($header)) continue;
                    $data = array_combine($header, $row);

                    $code = trim($data['kode_barang'] ?? '');
                    $name = trim($data['nama_barang'] ?? '');
                    $category = trim($data['kategori'] ?? '');
                    $supplierName = trim($data['supplier'] ?? '');
                    
                    $leadTime = (int)($data['lead_time'] ?? 2);
                    $maxLeadTime = (int)($data['max_lead_time'] ?? 4);

                    // Tentukan supplier model
                    $supplierObj = $suppliersMap[$supplierName] ?? $defaultSupplier;
                    
                    $stokAwal = null;
                    if (isset($data['stok_awal']) && $data['stok_awal'] !== '') {
                        $stokAwal = (int)$data['stok_awal'];
                    }

                    $key = $code ?: $name;
                    $totalSold = $totalSoldMap[$key] ?? 0;
                    
                    if (is_null($stokAwal)) {
                        $stokAwal = $totalSold + 15;
                    }

                    // Tentukan satuan berdasarkan nama barang
                    $unit = 'pcs';
                    $largeUnit = null;
                    $unitConversion = 1;
                    $lowerName = strtolower($name);
                    if (str_contains($lowerName, 'oli mesin')) {
                        $unit = 'pcs';
                        $largeUnit = 'dus';
                        $unitConversion = 24;
                    } elseif (str_contains($lowerName, 'set') || str_contains($lowerName, 'roller') || str_contains($lowerName, 'shockbreaker') || str_contains($lowerName, 'crashbar') || str_contains($lowerName, 'handgrip') || str_contains($lowerName, 'v-belt & per cvt')) {
                        $unit = 'set';
                        $largeUnit = 'box';
                        $unitConversion = 5;
                    } elseif (str_contains($lowerName, 'baut')) {
                        $unit = 'pcs';
                        $largeUnit = 'box';
                        $unitConversion = 100;
                    } elseif (str_contains($lowerName, 'busi')) {
                        $unit = 'pcs';
                        $largeUnit = 'box';
                        $unitConversion = 10;
                    }

                    $product = Product::create([
                        'code' => $code ?: null,
                        'name' => $name,
                        'category' => $category ?: null,
                        'unit' => $unit,
                        'large_unit' => $largeUnit,
                        'unit_conversion' => $unitConversion,
                        'stock' => $stokAwal,
                        'lead_time' => $leadTime,
                        'max_lead_time' => $maxLeadTime,
                        'supplier_id' => $supplierObj->id,
                        'ss_value' => 0,
                        'rop_value' => 0,
                    ]);

                    $productsMap[$key] = $product;
                    if ($code) {
                        $productsMap[$code] = $product;
                    }
                    if ($name) {
                        $productsMap[$name] = $product;
                    }
                }
            }
            fclose($handle);
        }

        // Parse & import Barang Masuk (incoming_transactions.csv) jika ada
        if (File::exists($incomingPath) && ($handle = fopen($incomingPath, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ',');
            if ($header) {
                $header = array_map(fn($h) => trim($h, "\xEF\xBB\xBF\r\n\t "), $header);

                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    if (count($row) < count($header)) continue;
                    $tData = array_combine($header, $row);

                    $code = trim($tData['kode_barang'] ?? '');
                    $name = trim($tData['nama_barang'] ?? '');
                    $tanggal = trim($tData['tanggal'] ?? '');
                    $qty = (int)($tData['quantity'] ?? 0);
                    $supName = trim($tData['supplier'] ?? '');

                    $product = $productsMap[$code] ?? ($productsMap[$name] ?? null);

                    if ($product && $qty > 0) {
                        Transaction::create([
                            'product_id' => $product->id,
                            'type' => 'in',
                            'quantity' => $qty,
                            'transaction_date' => $tanggal,
                            'notes' => 'Barang Masuk. Supplier: ' . $supName,
                        ]);

                        // Tambahkan stok barang dari transaksi Barang Masuk (restock)
                        $product->stock += $qty;
                        $product->save();
                    }
                }
            }
            fclose($handle);
        }

        // Masukkan transaksi penjualan (barang keluar)
        foreach ($transactionsData as $tData) {
            $code = trim($tData['kode_barang'] ?? '');
            $name = trim($tData['nama_barang'] ?? '');
            $tanggal = trim($tData['tanggal'] ?? '');
            $noNota = trim($tData['no_nota'] ?? '');
            $qty = (int)($tData['quantity'] ?? 0);

            $product = $productsMap[$code] ?? ($productsMap[$name] ?? null);

            if (!$product) {
                continue;
            }

            // Buat transaksi keluar
            Transaction::create([
                'product_id' => $product->id,
                'type' => 'out',
                'quantity' => $qty,
                'transaction_date' => $tanggal,
                'notes' => 'Barang Keluar. No. Nota: ' . $noNota,
            ]);

            // Kurangi stok barang dari transaksi Barang Keluar (penjualan)
            $product->stock = max(0, $product->stock - $qty);
            $product->save();
        }

        // Hitung ulang SS dan ROP untuk seluruh barang setelah semua data penjualan masuk
        $allProducts = Product::all();
        foreach ($allProducts as $prod) {
            $prod->recalculateSSandROP();
            $prod->save();
        }
    }
}
