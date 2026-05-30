<?php

namespace App\Exports;

use App\Models\SystemBackup;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class BackupReportExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = SystemBackup::with('staff')
            ->join('staff_details', 'system_backups.staff_id', '=', 'staff_details.id')
            ->select('system_backups.*')
            ->orderBy('system_backups.backup_date', 'desc')
            ->orderBy('staff_details.name', 'asc');

        if ($this->request->filled('staff_id')) {
            $query->where('system_backups.staff_id', $this->request->staff_id);
        }

        if ($this->request->filter_type == 'month') {
            if ($this->request->filled('month')) {
                $query->whereMonth('system_backups.backup_date', $this->request->month);
            }
            if ($this->request->filled('year')) {
                $query->whereYear('system_backups.backup_date', $this->request->year);
            }
        } elseif ($this->request->filter_type == 'date_range') {
            if ($this->request->filled('start_date')) {
                $query->where('system_backups.backup_date', '>=', $this->request->start_date);
            }
            if ($this->request->filled('end_date')) {
                $query->where('system_backups.backup_date', '<=', $this->request->end_date);
            }
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Staff Name',
            'Backup Date',
            'Backup Taken',
            'Location',
            'Remark / Note',
        ];
    }

    public function map($backup): array
    {
        return [
            $backup->staff->name ?? 'Unknown',
            \Carbon\Carbon::parse($backup->backup_date)->format('d M Y'),
            $backup->status,
            $backup->location ?? '-',
            $backup->remark ?? '-',
        ];
    }
}
