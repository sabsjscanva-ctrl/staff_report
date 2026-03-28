<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'report_date',
        'pending_task',
        'planned_task',
        'comments',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function tasks()
    {
        return $this->hasMany(DailyReportTask::class);
    }
}
