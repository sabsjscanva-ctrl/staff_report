<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TaskReportExport implements FromArray, WithHeadings, WithStyles, WithTitle
{
    protected $title;
    protected $historyData;
    protected $totalTimeFormatted;
    protected $staffName;

    public function __construct($title, $historyData, $totalTimeFormatted, $staffName)
    {
        $this->title = $title;
        $this->historyData = $historyData;
        $this->totalTimeFormatted = $totalTimeFormatted;
        $this->staffName = $staffName;
    }

    public function array(): array
    {
        $rows = [];
        // Add meta info rows
        $rows[] = ['Staff Name:', $this->staffName, '', ''];
        $rows[] = ['Task Name:', $this->title, '', ''];
        $rows[] = ['Total Time:', $this->totalTimeFormatted, '', ''];
        $rows[] = ['', '', '', '']; // empty row

        // Add history rows
        foreach ($this->historyData as $data) {
            // Description might have HTML entities or line breaks
            $desc = strip_tags($data['description'] ?? '');
            
            $rows[] = [
                $data['date'] ?? '',
                ucfirst($data['status'] ?? ''),
                $data['time_spend'] ?? '',
                $desc
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Status',
            'Time Spent',
            'Description/Updates',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]], // Headings
            5    => ['font' => ['bold' => true]], // The table headers
        ];
    }

    public function title(): string
    {
        return 'Task Report';
    }
}
