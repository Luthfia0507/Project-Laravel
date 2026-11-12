<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleReturnItem extends Model
{
    protected $fillable = [
        'sale_return_id',
        'sale_item_id',
        'quantity',
        'condition',
        'unit_price',
        'subtotal',
        'replacement_product_id',
        'replacement_quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'replacement_quantity' => 'integer',
    ];

    public function saleReturn()
    {
        return $this->belongsTo(SaleReturn::class);
    }

    public function saleItem()
    {
        return $this->belongsTo(SaleItem::class);
    }

    public function replacementProduct()
    {
        return $this->belongsTo(
            Product::class,
            'replacement_product_id'
        );
    }
}
