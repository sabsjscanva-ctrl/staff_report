<?php

namespace App\Http\Controllers;

use App\Models\StockCategory;
use App\Models\StockItem;
use App\Models\StockAllotment;
use App\Models\Staff\StaffModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockManagementController extends Controller
{
    public function categoryIndex()
    {
        $categories = StockCategory::all();
        return view('StockManagement.categories', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $request->validate(['name' => 'required|unique:stock_categories']);
        StockCategory::create($request->all());
        return back()->with('success', 'Category created successfully');
    }

    public function itemIndex(Request $request)
    {
        $categories = StockCategory::all();
        
        $query = StockItem::with('category')->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $items = $query->paginate(10)->withQueryString();
        
        return view('StockManagement.items', compact('categories', 'items'));
    }

    public function itemStore(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:stock_categories,id',
            'name' => 'required',
            'quantity' => 'required|integer|min:0',
        ]);
        StockItem::create($request->all());
        return back()->with('success', 'Item added to stock');
    }

    public function itemUpdate(Request $request, $id)
    {
        $request->validate([
            'details' => 'nullable|string',
            'remark' => 'nullable|string',
        ]);

        $item = StockItem::findOrFail($id);
        $item->update($request->only(['details', 'remark']));

        return back()->with('success', 'Item updated successfully');
    }

    public function allotmentIndex()
    {
        $staffs = StaffModel::all();
        $items = StockItem::where('quantity', '>', 0)->get();
        $allotments = StockAllotment::with(['staff', 'item.category'])->get();
        return view('StockManagement.allotments', compact('staffs', 'items', 'allotments'));
    }

    public function allotmentStore(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff_details,id',
            'item_id' => 'required|exists:stock_items,id',
            'quantity' => 'required|integer|min:1',
            'allotment_date' => 'required|date',
        ]);

        $item = StockItem::find($request->item_id);

        if ($item->quantity < $request->quantity) {
            return back()->with('error', 'Insufficient stock quantity');
        }

        DB::transaction(function () use ($request, $item) {
            StockAllotment::create($request->all());
            $item->decrement('quantity', $request->quantity);
        });

        return back()->with('success', 'Stock allotted successfully');
    }
}
