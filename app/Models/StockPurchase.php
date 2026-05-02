<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockPurchase extends Model
{
    protected $fillable = [
        'item_id',
        'brand_id', // Changed from item_id
        'quantity',
        'purchase_date',
        'vendor_name',
        'invoice_no',
        'amount',
        'remark'
    ];

    public function brand()
    {
        return $this->belongsTo(StockItemBrand::class, 'brand_id');
    }

    public function item() // Keep for compatibility if needed, but point to item through brand
    {
        return $this->brand->item();
    }
}
