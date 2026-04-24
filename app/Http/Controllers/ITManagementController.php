<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff\StaffModel;
use App\Models\SystemAllotment;
use App\Models\SystemBackup;

class ITManagementController extends Controller
{
    public function allotmentIndex()
    {
        $staffs = StaffModel::with(['department', 'systemAllotment', 'stockAllotments.item'])->where('status', 'Active')->get();
        return view('ITManagement.allotment', compact('staffs'));
    }

    public function allotmentStore(Request $request)
    {
        $data = $request->validate([
            'staff_id' => 'required|exists:staff_details,id',
            'type' => 'nullable|string',
            'processor' => 'nullable|string',
            'ram' => 'nullable|string',
            'storage' => 'nullable|string',
            'motherboard' => 'nullable|string',
            'graphic_card' => 'nullable|string',

            'operating_system' => 'nullable|string',
            'licensed_software' => 'nullable|string',
            'antivirus' => 'nullable|string',
            'installed_applications' => 'nullable|string',
            'ip_address' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $allotment = SystemAllotment::updateOrCreate(
            ['staff_id' => $data['staff_id']],
            $data
        );

        return back()->with('success', 'System Allotment updated successfully.');
    }

    public function backupIndex()
    {
        $staffs = StaffModel::with(['department', 'systemBackups' => function($q) {
            $q->orderBy('backup_date', 'desc');
        }])->where('status', 'Active')->get();
        return view('ITManagement.backup', compact('staffs'));
    }

    public function backupStore(Request $request)
    {
        $data = $request->validate([
            'staff_id' => 'required|exists:staff_details,id',
            'status' => 'nullable|string',
            'location' => 'nullable|string',
            'remark' => 'nullable|string',
            'backup_date' => 'nullable|date',
        ]);

        SystemBackup::create($data);

        return back()->with('success', 'Backup info added successfully.');
    }
}
