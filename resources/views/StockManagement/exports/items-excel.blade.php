<table>
    <thead>
        <tr>
            <th>Category</th>
            <th>Item Type</th>
            <th>Brand / Model</th>
            <th>Available Quantity</th>
            <th>Dump Quantity</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
            @php 
                $categoryItems = $items->where('category_id', $category->id);
            @endphp
            @foreach($categoryItems as $item)
                @foreach($item->brands as $brand)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $brand->name }}</td>
                        <td>{{ $brand->quantity }}</td>
                        <td>{{ $brand->dump_quantity ?? 0 }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
    </tbody>
</table>
