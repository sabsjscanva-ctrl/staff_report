<?php

namespace App\Models\Office;

use Illuminate\Database\Eloquent\Model;

class DepartmentModel extends Model
{
    protected $table = 'departments';

    protected $fillable = [
        'name',
        'status',
    ];
}
