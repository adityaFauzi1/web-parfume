<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
        'purchase_price',
        'subtotal',
        'total_cost',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'price' => 'integer',
            'purchase_price' => 'integer',
            'subtotal' => 'integer',
            'total_cost' => 'integer',
        ];
    }

    /**
     * Transaksi yang memiliki item ini.
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Produk yang terjual.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}