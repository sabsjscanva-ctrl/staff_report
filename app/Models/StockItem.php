<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'quantity', 'details', 'remark'];

    public function category()
    {
        return $this->belongsTo(StockCategory::class, 'category_id');
    }

    public function allotments()
    {
        return $this->hasMany(StockAllotment::class, 'item_id');
    }
}
