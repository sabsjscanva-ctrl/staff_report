<?php

namespace App\Exports;

use App\Models\StockCategory;
use App\Models\StockItem;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockItemExport implements FromView, ShouldAutoSize, WithStyles
{
    public function view(): View
    {
        $categories = StockCategory::all();
        $items = StockItem::with(['category', 'brands'])->get();

        return view('StockManagement.exports.items-excel', [
            'categories' => $categories,
            'items' => $items
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
