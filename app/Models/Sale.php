<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'invoice',
        'customer_name',
        'customer_phone',
        'grand_total',
    ];

    protected function casts(): array
    {
        return [
            'grand_total' => 'integer',
        ];
    }

    /**
     * Item-item yang terjual dalam transaksi ini.
     */
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Total omzet (pendapatan kotor) transaksi ini.
     */
    public function getRevenueAttribute(): int
    {
        return $this->items->sum('subtotal');
    }

    /**
     * Total modal / HPP transaksi ini.
     */
    public function getTotalCostAttribute(): int
    {
        return $this->items->sum('total_cost');
    }

    /**
     * Keuntungan bersih transaksi ini.
     */
    public function getProfitAttribute(): int
    {
        return $this->getRevenueAttribute() - $this->getTotalCostAttribute();
    }

    /**
     * Format nomor invoice.
     */
    public function getInvoiceAttribute(string $value): string
    {
        return strtoupper($value);
    }
}