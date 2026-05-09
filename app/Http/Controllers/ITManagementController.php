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

    public function backupIndex(Request $request)
    {
        $offices = \App\Models\Office\OfficeModel::all();
        
        $query = StaffModel::with(['department', 'systemBackups' => function($q) use ($request) {
            if ($request->specific_date) {
                $q->where('backup_date', $request->specific_date);
            }
            $q->orderBy('backup_date', 'desc');
        }])->where('status', 'Active');

        if ($request->office_id) {
            $query->where('office_id', $request->office_id);
        }

        $staffs = $query->orderByRaw('CASE WHEN backup_sequence IS NULL THEN 9999 ELSE backup_sequence END')
                        ->orderBy('name')
                        ->get();

        return view('ITManagement.backup', compact('staffs', 'offices'));
    }

    public function backupStore(Request $request)
    {
        // Handle Staff Sequencing with Smart Reshuffle
        if ($request->has('sequences')) {
            foreach ($request->sequences as $staffId => $newSeq) {
                if ($newSeq === null || $newSeq === '') continue;
                
                $staff = StaffModel::find($staffId);
                $oldSeq = $staff->backup_sequence;
                
                if ($oldSeq != $newSeq) {
                    if ($oldSeq === null) {
                        // Brand new sequence: Shift everything from newSeq onwards up
                        StaffModel::where('id', '!=', $staffId)
                            ->where('backup_sequence', '>=', $newSeq)
                            ->increment('backup_sequence');
                    } elseif ($newSeq < $oldSeq) {
                        // Moving UP (e.g., 6 to 3): Everything from 3 to 5 shifts UP (+1)
                        StaffModel::where('id', '!=', $staffId)
                            ->where('backup_sequence', '>=', $newSeq)
                            ->where('backup_sequence', '<', $oldSeq)
                            ->increment('backup_sequence');
                    } else {
                        // Moving DOWN (e.g., 3 to 6): Everything from 4 to 6 shifts DOWN (-1)
                        StaffModel::where('id', '!=', $staffId)
                            ->where('backup_sequence', '>', $oldSeq)
                            ->where('backup_sequence', '<=', $newSeq)
                            ->decrement('backup_sequence');
                    }
                    $staff->update(['backup_sequence' => $newSeq]);
                }
            }
        }

        // Handle Bulk Backups
        if ($request->has('backups')) {
            $count = 0;
            foreach ($request->backups as $staffId => $dates) {
                foreach ($dates as $date => $details) {
                    // Only process if status is set (YES, NO, NA, etc)
                    if (!empty($details['status'])) {
                        SystemBackup::updateOrCreate(
                            ['staff_id' => $staffId, 'backup_date' => $date],
                            [
                                'status' => $details['status'],
                                'location' => $details['location'] ?? null,
                                'remark' => $details['remark'] ?? null,
                            ]
                        );
                        $count++;
                    }
                }
            }
            return back()->with('success', "Updated $count backup records and staff sequences.");
        }

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
