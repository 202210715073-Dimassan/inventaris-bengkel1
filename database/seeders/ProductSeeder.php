<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Oli Mesin Motul 5100 10W-40', 'stock' => 50, 'unit' => 'botol', 'lead_time' => 2, 'max_lead_time' => 4],
            ['name' => 'Busi NGK C7HSA Vespa', 'stock' => 100, 'unit' => 'pcs', 'lead_time' => 1, 'max_lead_time' => 2],
            ['name' => 'Ban Pirelli Angel Scooter 110/70', 'stock' => 30, 'unit' => 'pcs', 'lead_time' => 5, 'max_lead_time' => 7],
            ['name' => 'V-Belt Mitsuboshi Vespa Matic', 'stock' => 20, 'unit' => 'pcs', 'lead_time' => 3, 'max_lead_time' => 5],
            ['name' => 'Kampas Rem Depan Vespa Sprint', 'stock' => 40, 'unit' => 'set', 'lead_time' => 2, 'max_lead_time' => 4],
            ['name' => 'Kampas Rem Belakang Piaggio', 'stock' => 45, 'unit' => 'set', 'lead_time' => 2, 'max_lead_time' => 4],
            ['name' => 'Filter Udara Ferrox', 'stock' => 15, 'unit' => 'pcs', 'lead_time' => 4, 'max_lead_time' => 6],
            ['name' => 'Roller Kawahara 12g', 'stock' => 60, 'unit' => 'set', 'lead_time' => 2, 'max_lead_time' => 3],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
