<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'purchase_price',
        'size',
        'gender',
        'stock',
        'min_stock',
        'image',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'price' => 'integer',
            'purchase_price' => 'integer',
            'stock' => 'integer',
            'min_stock' => 'integer',
        ];
    }

    /**
     * Kategori dari produk ini.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Format harga dalam Rupiah.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Format harga beli (modal) dalam Rupiah.
     */
    public function getFormattedPurchasePriceAttribute(): string
    {
        return 'Rp ' . number_format($this->purchase_price, 0, ',', '.');
    }

    /**
     * Keuntungan per unit (harga jual - harga beli).
     */
    public function getProfitPerUnitAttribute(): int
    {
        return $this->price - $this->purchase_price;
    }

    /**
     * Persentase margin keuntungan per unit.
     */
    public function getMarginPercentAttribute(): float
    {
        if ($this->price <= 0) {
            return 0.0;
        }

        return round(($this->profit_per_unit / $this->price) * 100, 1);
    }

    /**
     * Status ketersediaan stok.
     */
    public function getIsAvailableAttribute(): bool
    {
        return $this->is_active && $this->stock > 0;
    }

    /**
     * Status stok menipis (di bawah batas minimum).
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->is_active && $this->stock <= $this->min_stock;
    }

    /**
     * Scope: hanya produk aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: produk unggulan.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope: stok menipis / perlu restock.
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'min_stock');
    }
}