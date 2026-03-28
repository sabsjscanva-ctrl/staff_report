<?php

namespace App\Http\Controllers;

use App\Models\Staff\StaffModel;
use App\Models\DailyReport;
use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:staff']);
    }

    public function index()
    {
        $staffDetail = StaffModel::with(['department', 'office'])
            ->where('user_id', Auth::id())
            ->first();

        $totalReports = DailyReport::where('staff_id', Auth::id())->count();
        $todayReport  = DailyReport::where('staff_id', Auth::id())
            ->whereDate('report_date', today())
            ->first();
        $recentReports = DailyReport::with('tasks')
            ->where('staff_id', Auth::id())
            ->orderByDesc('report_date')
            ->limit(5)
            ->get();

        return view('staff.dashboard', compact('staffDetail', 'totalReports', 'todayReport', 'recentReports'));
    }
}
