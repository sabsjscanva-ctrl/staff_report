<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ITTicketExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $tickets;

    public function __construct($tickets)
    {
        $this->tickets = $tickets;
    }

    public function collection()
    {
        return $this->tickets;
    }

    public function headings(): array
    {
        return [
            'Ticket ID',
            'Requester Name',
            'Department',
            'Category',
            'Subject',
            'Status',
            'Raised At',
            'Started At',
            'Completed At',
            'Resolution Time',
            'Remarks'
        ];
    }

    public function map($ticket): array
    {
        $s = $ticket->total_seconds_spent;
        $hh = floor($s / 3600);
        $mm = floor(($s % 3600) / 60);
        if ($s > 0 && $s < 60) $mm = 1;
        $timeStr = "{$hh}h {$mm}m";

        return [
            '#' . str_pad($ticket->id, 5, '0', STR_PAD_LEFT),
            $ticket->staff->name,
            $ticket->staff->staff->department->name ?? 'N/A',
            $ticket->category,
            $ticket->subject,
            $ticket->status,
            $ticket->created_at->format('d M Y, h:i A'),
            $ticket->started_at ? $ticket->started_at->format('d M Y, h:i A') : 'N/A',
            $ticket->completed_at ? $ticket->completed_at->format('d M Y, h:i A') : 'N/A',
            $timeStr,
            $ticket->remarks ?? 'No remarks'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
