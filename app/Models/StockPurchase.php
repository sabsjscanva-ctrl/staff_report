<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockPurchase extends Model
{
    protected $fillable = [
        'item_id',
        'quantity',
        'purchase_date',
        'vendor_name',
        'invoice_no',
        'amount',
        'remark'
    ];

    public function item()
    {
        return $this->belongsTo(StockItem::class, 'item_id');
    }
}
