<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItemBrand extends Model
{
    use HasFactory;

    protected $fillable = ['stock_item_id', 'name', 'quantity', 'details', 'remark'];

    public function item()
    {
        return $this->belongsTo(StockItem::class, 'stock_item_id');
    }

    public function purchases()
    {
        return $this->hasMany(StockPurchase::class, 'brand_id');
    }

    public function allotments()
    {
        return $this->hasMany(StockAllotment::class, 'brand_id');
    }
}
