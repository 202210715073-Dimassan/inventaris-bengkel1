<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'transaction_unit',
        'original_quantity',
        'transaction_date',
        'notes'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Sesuai Class Diagram: Catat transaksi barang masuk.
     */
    public static function recordIncoming(array $attributes): self
    {
        $attributes['type'] = 'in';
        return static::create($attributes);
    }

    /**
     * Sesuai Class Diagram: Catat transaksi barang keluar.
     */
    public static function recordOutgoing(array $attributes): self
    {
        $attributes['type'] = 'out';
        return static::create($attributes);
    }

    /**
     * Sesuai Class Diagram: Update stok produk berdasarkan tipe transaksi.
     */
    public function updateStock(): void
    {
        if ($this->product) {
            if ($this->type === 'in') {
                $this->product->stock += $this->quantity;
            } else {
                $this->product->stock -= $this->quantity;
            }
            $this->product->recalculateSSandROP();
            $this->product->save();
        }
    }
}
