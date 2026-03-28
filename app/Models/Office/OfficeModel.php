<?php

namespace App\Models\Office;

use Illuminate\Database\Eloquent\Model;

class OfficeModel extends Model
{
    protected $table = 'office_details';

    protected $fillable = [
        'name',
        'city',
        'address',
        'status',
    ];
}
