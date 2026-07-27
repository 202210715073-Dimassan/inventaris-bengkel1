<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $now = Carbon::now();

        foreach ($products as $product) {
            // Generate some random outgoing transactions over the last 14 days
            $daysToSeed = 14;
            $dailyUsageMap = [];

            for ($i = 0; $i < $daysToSeed; $i++) {
                // Randomize if there was a transaction on this day
                if (rand(1, 100) > 40) { 
                    $qty = rand(1, 5); // Random quantity
                    
                    Transaction::create([
                        'product_id' => $product->id,
                        'type' => 'out',
                        'quantity' => $qty,
                        'transaction_date' => $now->copy()->subDays($i)->format('Y-m-d'),
                        'notes' => 'Seeded transaction',
                    ]);

                    $dailyUsageMap[] = $qty;
                }
            }

            // Calculate SS & ROP
            if (count($dailyUsageMap) > 0) {
                $maxUsage = max($dailyUsageMap);
                $avgUsage = array_sum($dailyUsageMap) / count($dailyUsageMap);
                
                $product->max_usage = $maxUsage;
                $product->average_usage = $avgUsage;
                
                $maxLeadTime = $product->max_lead_time;
                $avgLeadTime = $product->lead_time;
                
                $ss = ($maxUsage * $maxLeadTime) - ($avgUsage * $avgLeadTime);
                $product->ss_value = max(0, ceil($ss));
                
                $product->rop_value = ceil(($avgUsage * $avgLeadTime) + $product->ss_value);
                
                // Adjust stock to trigger some critical states randomly
                if (rand(1, 100) > 70) {
                    $product->stock = rand(0, (int)$product->rop_value); // Set stock below or equal to ROP
                }

                $product->save();
            }
        }
    }
}
