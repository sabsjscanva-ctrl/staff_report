<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemBackup extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'status',
        'location',
        'remark',
        'backup_date',
    ];

    public function staff()
    {
        return $this->belongsTo(\App\Models\Staff\StaffModel::class, 'staff_id');
    }
}
