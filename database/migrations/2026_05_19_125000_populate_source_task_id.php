<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Get all tasks that are carried forward
        $carryTasks = DB::table('daily_report_tasks as t')
            ->join('daily_reports as r', 't.daily_report_id', '=', 'r.id')
            ->select('t.id', 't.task_title', 'r.staff_id', 'r.report_date', 'r.id as report_id')
            ->where('t.is_carry', true)
            ->orderBy('r.report_date', 'asc')
            ->orderBy('r.id', 'asc')
            ->orderBy('t.id', 'asc')
            ->get();

        foreach ($carryTasks as $ct) {
            // Find candidate source tasks from previous reports (belonging to same staff, before the carry report date)
            $sourceTask = DB::table('daily_report_tasks as t2')
                ->join('daily_reports as r2', 't2.daily_report_id', '=', 'r2.id')
                ->where('r2.staff_id', $ct->staff_id)
                ->where('t2.task_title', $ct->task_title)
                ->where(function($q) use ($ct) {
                    $q->where('r2.report_date', '<', $ct->report_date)
                      ->orWhere(function($q2) use ($ct) {
                          $q2->where('r2.report_date', '=', $ct->report_date)
                             ->where('r2.id', '<', $ct->report_id);
                      });
                })
                // Ensure this source task hasn't already been mapped to another carry task
                ->whereNotExists(function($query) {
                    $query->select(DB::raw(1))
                        ->from('daily_report_tasks as t3')
                        ->whereRaw('t3.source_task_id = t2.id');
                })
                ->orderByDesc('r2.report_date')
                ->orderByDesc('r2.id')
                ->orderByDesc('t2.id')
                ->select('t2.id')
                ->first();

            if ($sourceTask) {
                DB::table('daily_report_tasks')
                    ->where('id', $ct->id)
                    ->update(['source_task_id' => $sourceTask->id]);
            }
        }
    }

    public function down(): void
    {
        DB::table('daily_report_tasks')->update(['source_task_id' => null]);
    }
};
