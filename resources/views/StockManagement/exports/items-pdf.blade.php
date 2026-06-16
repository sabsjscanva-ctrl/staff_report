<!DOCTYPE html>
<html>
<head>
    <title>Overall Stock Report</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; vertical-align: top; }
        th { background-color: #f4f4f4; font-weight: bold; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
        .category-header { background-color: #eef2ff; padding: 8px; font-weight: bold; font-size: 13px; margin-top: 20px;}
        .small-text { font-size: 9px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin: 0; color: #1f2937;">Overall Stock Report</h2>
        <p style="margin: 5px 0 0 0; color: #6b7280;">Generated on: {{ date('d M, Y H:i') }}</p>
    </div>

    @foreach($categories as $category)
        <div class="category-header">{{ $category->name }}</div>
        <table>
            <thead>
                <tr>
                    <th width="30%">Item Type</th>
                    <th width="30%">Brand / Model</th>
                    <th width="20%">Available Quantity</th>
                    <th width="20%">Dump Quantity</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $hasItems = false; 
                    // To group by items in this category
                    $categoryItems = $items->where('category_id', $category->id);
                @endphp
                @foreach($categoryItems as $item)
                    @foreach($item->brands as $brand)
                        @php $hasItems = true; @endphp
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $brand->name }}</td>
                            <td>{{ $brand->quantity }}</td>
                            <td>{{ $brand->dump_quantity ?? 0 }}</td>
                        </tr>
                    @endforeach
                @endforeach
                @if(!$hasItems)
                    <tr>
                        <td colspan="4" style="text-align: center; color: #999;">No items in this category.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    @endforeach
</body>
</html>
