<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Products Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #059669;
        }
        .header h1 {
            font-size: 24px;
            color: #047857;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header .subtitle {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }
        .header .date {
            font-size: 10px;
            color: #9ca3af;
            margin-top: 3px;
        }

        /* Filter Info Section */
        .filter-info {
            margin-bottom: 20px;
            background: #eff6ff;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #bfdbfe;
        }
        .filter-info .title {
            font-size: 10px;
            font-weight: 600;
            color: #1e40af;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .filter-info .filter-grid {
            display: table;
            width: 100%;
        }
        .filter-info .filter-row {
            display: table-row;
        }
        .filter-info .filter-item {
            display: table-cell;
            padding: 5px 10px;
            width: 25%;
        }
        .filter-info .filter-label {
            display: block;
            font-size: 8px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .filter-info .filter-value {
            display: block;
            font-size: 10px;
            font-weight: 600;
            color: #1f2937;
        }
        
        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #047857;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            border: 1px solid #065f46;
        }
        td {
            padding: 8px;
            border: 1px solid #e5e7eb;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        /* Badges */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
        }
        .badge-active { background: #d1fae5; color: #065f46; }
        .badge-inactive { background: #fee2e2; color: #991b1b; }
        .badge-reward { background: #fef3c7; color: #92400e; }
        .badge-regular { background: #dbeafe; color: #1e40af; }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40px;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
            padding: 10px;
            border-top: 1px solid #e5e7eb;
        }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .text-sm { font-size: 9px; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Products Report</h1>
        <div class="subtitle">Complete Product Catalog with Stock and Pricing</div>
        <div class="date">Generated on {{ now()->format('l, d F Y H:i:s') }}</div>
    </div>

    @if(request('category_id') || request('is_active') !== null || request('is_reward') !== null)
    <div class="filter-info">
        <div class="title">Applied Filters</div>
        <div class="filter-grid">
            <div class="filter-row">
                @if(request('category_id'))
                <div class="filter-item">
                    <span class="filter-label">Category</span>
                    <span class="filter-value">{{ \App\Models\Category::find(request('category_id'))->name ?? 'N/A' }}</span>
                </div>
                @endif
                
                @if(request('is_active') !== null)
                <div class="filter-item">
                    <span class="filter-label">Status</span>
                    <span class="filter-value">{{ request('is_active') == '1' ? 'Active' : 'Inactive' }}</span>
                </div>
                @endif
                
                @if(request('is_reward') !== null)
                <div class="filter-item">
                    <span class="filter-label">Type</span>
                    <span class="filter-value">{{ request('is_reward') == '1' ? 'Reward Product' : 'Regular Product' }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Products Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 25%;">Product Name</th>
                <th style="width: 15%;">Category</th>
                <th style="width: 12%;" class="text-right">Price</th>
                <th style="width: 10%;" class="text-right">Point Price</th>
                <th style="width: 10%;" class="text-center">Variations</th>
                <th style="width: 10%;" class="text-center">Stock</th>
                <th style="width: 10%;">Type</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $index => $product)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="font-bold">{{ $product->name }}</td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                <td class="text-right">{{ $product->point_price ? number_format($product->point_price) : '-' }}</td>
                <td class="text-center">{{ $product->variations->count() }}</td>
                <td class="text-center font-bold">{{ $product->variations->sum('stock') }}</td>
                <td>
                    <span class="badge badge-{{ $product->is_reward ? 'reward' : 'regular' }}">
                        {{ $product->is_reward ? 'Reward' : 'Regular' }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-{{ $product->is_active ? 'active' : 'inactive' }}">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center" style="padding: 20px; color: #6b7280;">
                    No products found
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div>Fashion E-Commerce Report System | This is a computer-generated document</div>
        <div>Page generated at {{ now()->format('Y-m-d H:i:s') }}</div>
    </div>
</body>
</html>
