<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyReportTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_report_id',
        'task_title',
        'description',
        'status',
        'time_spend',
    ];

    public function dailyReport()
    {
        return $this->belongsTo(DailyReport::class);
    }
}
