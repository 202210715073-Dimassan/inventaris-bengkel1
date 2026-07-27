<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code',
        'name',
        'category',
        'unit',
        'large_unit',
        'unit_conversion',
        'supplier_id',
        'stock',
        'lead_time',
        'max_lead_time',
        'average_usage',
        'max_usage',
        'ss_value',
        'rop_value',
    ];

    /**
     * Accessor untuk format stok gabungan (contoh: 50 botol (2 dus & 2 botol)).
     */
    public function getFormattedStockAttribute(): string
    {
        if ($this->large_unit && $this->unit_conversion > 1) {
            $largeQty = floor($this->stock / $this->unit_conversion);
            $baseQty = $this->stock % $this->unit_conversion;

            if ($largeQty > 0) {
                if ($baseQty > 0) {
                    return "{$this->stock} {$this->unit} ({$largeQty} {$this->large_unit} & {$baseQty} {$this->unit})";
                }
                return "{$this->stock} {$this->unit} ({$largeQty} {$this->large_unit})";
            }
        }
        
        return "{$this->stock} {$this->unit}";
    }

    /**
     * Format ROP dalam satuan besar/kecil jika ada.
     */
    public function getFormattedRopAttribute(): string
    {
        $rop = round($this->rop_value);
        if ($this->large_unit && $this->unit_conversion > 1 && $rop >= $this->unit_conversion) {
            $largeQty = floor($rop / $this->unit_conversion);
            $baseQty = $rop % $this->unit_conversion;

            if ($baseQty > 0) {
                return "{$rop} {$this->unit} (~{$largeQty} {$this->large_unit} & {$baseQty} {$this->unit})";
            }
            return "{$rop} {$this->unit} (~{$largeQty} {$this->large_unit})";
        }
        return "{$rop} {$this->unit}";
    }

    /**
     * Relasi: Setiap produk dimiliki oleh satu supplier.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relasi: Satu produk memiliki banyak transaksi.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Hitung ulang Safety Stock & ROP berdasarkan data transaksi keluar.
     * Otomatis dipanggil dari TransactionController.
     */
    public function recalculateSSandROP(): void
    {
        $outTransactions = $this->transactions()
            ->where('type', 'out')
            ->get();

        if ($outTransactions->count() > 0) {
            $dailyUsage = $outTransactions
                ->groupBy('transaction_date')
                ->map(fn($rows) => $rows->sum('quantity'));

            $this->max_usage = $dailyUsage->max();
            
            // Total penjualan selama periode
            $totalSales = $outTransactions->sum('quantity');
            
            // Asumsi periode adalah 30 hari berdasarkan perhitungan manual user
            $this->average_usage = $totalSales / 30;

            $ss = ($this->max_usage * $this->max_lead_time)
                - ($this->average_usage * $this->lead_time);

            $this->ss_value  = max(0, $ss); 
            
            // Kita hitung persis seperti manual:
            $this->ss_value = max(0, round($ss, 2));
            $this->rop_value = round(($this->average_usage * $this->lead_time) + $this->ss_value, 2);
            
            // Simpan sebagai nilai bulat (ceil) untuk stok fisik
            $this->ss_value = ceil($this->ss_value);
            $this->rop_value = ceil($this->rop_value);
        }
    }

    /**
     * Sesuai Class Diagram: Hitung Safety Stock (SS).
     */
    public function calculateSafetyStock(): float
    {
        return (float) $this->ss_value;
    }

    /**
     * Sesuai Class Diagram: Hitung Reorder Point (ROP).
     */
    public function calculateReorderPoint(): float
    {
        return (float) $this->rop_value;
    }

    /**
     * Sesuai Class Diagram: Cek status apakah barang perlu di-restock.
     */
    public function checkRestockStatus(): string
    {
        if ($this->stock <= $this->rop_value) {
            return 'Restock (Di Bawah ROP)';
        }
        return 'Stok Aman';
    }
}
