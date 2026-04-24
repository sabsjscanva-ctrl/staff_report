<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemAllotment extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'type',
        'processor',
        'ram',
        'storage',
        'motherboard',
        'graphic_card',

        'operating_system',
        'licensed_software',
        'antivirus',
        'installed_applications',
        'ip_address',
        'remarks',
    ];

    public function staff()
    {
        return $this->belongsTo(\App\Models\Staff\StaffModel::class, 'staff_id');
    }
}
