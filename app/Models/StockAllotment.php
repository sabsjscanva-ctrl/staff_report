<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAllotment extends Model
{
    use HasFactory;

    protected $fillable = ['staff_id', 'item_id', 'quantity', 'allotment_date', 'remark'];

    public function staff()
    {
        return $this->belongsTo(\App\Models\Staff\StaffModel::class, 'staff_id');
    }

    public function item()
    {
        return $this->belongsTo(StockItem::class, 'item_id');
    }
}
