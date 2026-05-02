<?php

namespace App\Http\Controllers;

use App\Models\StockCategory;
use App\Models\StockItem;
use App\Models\StockItemBrand;
use App\Models\StockAllotment;
use App\Models\StockPurchase;
use App\Models\Staff\StaffModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockManagementController extends Controller
{
    public function purchaseIndex()
    {
        $brands = StockItemBrand::with('item.category')->get();
        $purchases = StockPurchase::with('brand.item.category')->latest()->paginate(10);
        return view('StockManagement.purchases', compact('brands', 'purchases'));
    }

    public function purchaseStore(Request $request)
    {
        $request->validate([
            'brand_id' => 'required|exists:stock_item_brands,id',
            'quantity' => 'required|integer|min:1',
            'purchase_date' => 'required|date',
            'vendor_name' => 'nullable|string',
            'invoice_no' => 'nullable|string',
            'amount' => 'nullable|numeric',
        ]);

        DB::transaction(function () use ($request) {
            $brand = StockItemBrand::findOrFail($request->brand_id);
            $data = $request->all();
            $data['item_id'] = $brand->stock_item_id;
            StockPurchase::create($data);
            $brand->increment('quantity', $request->quantity);
        });

        return back()->with('success', 'New purchase recorded and stock updated');
    }
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
        
        $query = StockItem::with(['category', 'brands'])->latest();

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
        ]);
        StockItem::create($request->all());
        return back()->with('success', 'Item Type created successfully');
    }

    public function brandStore(Request $request)
    {
        $request->validate([
            'stock_item_id' => 'required|exists:stock_items,id',
            'name' => 'required', // This is brand name
            'quantity' => 'required|integer|min:0',
        ]);
        StockItemBrand::create($request->all());
        return back()->with('success', 'Brand/Category added to Item');
    }

    public function itemUpdate(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:stock_categories,id',
            'name' => 'required',
        ]);

        $item = StockItem::findOrFail($id);
        $item->update($request->only(['category_id', 'name']));

        return back()->with('success', 'Item updated successfully');
    }

    public function itemDestroy($id)
    {
        $item = StockItem::findOrFail($id);
        $item->delete();
        return back()->with('success', 'Item Type and all its brands deleted successfully');
    }

    public function brandUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'quantity' => 'required|integer|min:0',
            'details' => 'nullable|string',
        ]);

        $brand = StockItemBrand::findOrFail($id);
        $brand->update($request->all());

        return back()->with('success', 'Brand updated successfully');
    }

    public function brandDestroy($id)
    {
        $brand = StockItemBrand::findOrFail($id);
        $brand->delete();
        return back()->with('success', 'Brand deleted successfully');
    }

    public function allotmentIndex()
    {
        $staffs = StaffModel::all();
        $brands = StockItemBrand::with('item.category')->where('quantity', '>', 0)->get();
        $allotments = StockAllotment::with(['staff', 'brand.item.category'])->get();
        return view('StockManagement.allotments', compact('staffs', 'brands', 'allotments'));
    }

    public function allotmentStore(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff_details,id',
            'items' => 'required|array|min:1',
            'items.*.brand_id' => 'required|exists:stock_item_brands,id',
            'items.*.quantity' => 'required|integer|min:1',
            'allotment_type' => 'required|in:Permanent,Temporary',
            'return_date' => 'required_if:allotment_type,Temporary|nullable|date',
            'allotment_date' => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($request) {
                foreach ($request->items as $itemData) {
                    $brand = StockItemBrand::findOrFail($itemData['brand_id']);

                    if ($brand->quantity < $itemData['quantity']) {
                        throw new \Exception("Insufficient stock for: " . $brand->item->name . " - " . $brand->name);
                    }

                    StockAllotment::create([
                        'staff_id' => $request->staff_id,
                        'item_id' => $brand->stock_item_id,
                        'brand_id' => $brand->id,
                        'quantity' => $itemData['quantity'],
                        'allotment_type' => $request->allotment_type,
                        'return_date' => $request->return_date,
                        'allotment_date' => $request->allotment_date,
                        'remark' => $request->remark,
                    ]);

                    $brand->decrement('quantity', $itemData['quantity']);
                }
            });

            return back()->with('success', 'Stock items allotted successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
