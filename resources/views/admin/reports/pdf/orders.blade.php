<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orders Report</title>
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
            border-bottom: 3px solid #2563eb;
        }
        .header h1 {
            font-size: 24px;
            color: #1e40af;
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
        
        /* Summary Section */
        .summary {
            margin-bottom: 20px;
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .summary-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .summary-row {
            display: table-row;
        }
        .summary-item {
            display: table-cell;
            padding: 10px;
            text-align: center;
            border-right: 1px solid #e5e7eb;
            width: 20%;
        }
        .summary-item:last-child {
            border-right: none;
        }
        .summary-item .label {
            display: block;
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .summary-item .value {
            display: block;
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
        }
        
        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #1e40af;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            border: 1px solid #1e3a8a;
        }
        td {
            padding: 8px;
            border: 1px solid #e5e7eb;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        tr:hover {
            background-color: #f3f4f6;
        }
        
        /* Badges */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
            text-align: center;
        }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-processing { background: #dbeafe; color: #1e40af; }
        .badge-shipped { background: #e9d5ff; color: #6b21a8; }
        .badge-delivered { background: #d1fae5; color: #065f46; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }
        .badge-paid { background: #d1fae5; color: #065f46; }
        .badge-failed { background: #fee2e2; color: #991b1b; }
        
        /* Footer */
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
        
        /* Page Break */
        .page-break {
            page-break-after: always;
        }
        
        /* Text Utilities */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .text-sm { font-size: 9px; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Orders Report</h1>
        <div class="subtitle">Complete Orders Data with Status and Payment Information</div>
        <div class="date">Generated on {{ now()->format('l, d F Y H:i:s') }}</div>
    </div>

    <!-- Filter Information -->
    @if(request('start_date') || request('end_date') || request('status') || request('payment_status'))
    <div class="filter-info">
        <div class="title">Applied Filters</div>
        <div class="filter-grid">
            <div class="filter-row">
                @if(request('start_date') || request('end_date'))
                <div class="filter-item">
                    <span class="filter-label">Date Range</span>
                    <span class="filter-value">
                        @if(request('start_date') && request('end_date'))
                            {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }} - {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
                        @elseif(request('start_date'))
                            From {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }}
                        @elseif(request('end_date'))
                            Until {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
                        @endif
                    </span>
                </div>
                @endif
                
                @if(request('status'))
                <div class="filter-item">
                    <span class="filter-label">Order Status</span>
                    <span class="filter-value">{{ request('status') }}</span>
                </div>
                @endif
                
                @if(request('payment_status'))
                <div class="filter-item">
                    <span class="filter-label">Payment Status</span>
                    <span class="filter-value">{{ request('payment_status') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Summary Section -->
    <div class="summary">
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-item">
                    <span class="label">Total Orders</span>
                    <span class="value">{{ number_format($summary['total_orders']) }}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Total Revenue</span>
                    <span class="value">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Shipping Cost</span>
                    <span class="value">Rp {{ number_format($summary['total_shipping'], 0, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Points Used</span>
                    <span class="value">{{ number_format($summary['total_points_used']) }}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Points Earned</span>
                    <span class="value">{{ number_format($summary['total_points_earned']) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 15%;">Order Number</th>
                <th style="width: 15%;">Customer</th>
                <th style="width: 12%;">Date</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 10%;">Payment</th>
                <th style="width: 8%;">Items</th>
                <th style="width: 12%;" class="text-right">Shipping</th>
                <th style="width: 13%;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $index => $order)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="font-bold">{{ $order->order_number }}</td>
                <td>
                    <div>{{ $order->user->name ?? '-' }}</div>
                    <div class="text-sm" style="color: #6b7280;">{{ $order->user->email ?? '-' }}</div>
                </td>
                <td>
                    <div>{{ $order->created_at->format('d M Y') }}</div>
                    <div class="text-sm" style="color: #6b7280;">{{ $order->created_at->format('H:i') }}</div>
                </td>
                <td>
                    <span class="badge badge-{{ strtolower($order->status) }}">
                        {{ $order->status }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-{{ strtolower($order->payment_status) }}">
                        {{ $order->payment_status }}
                    </span>
                </td>
                <td class="text-center">{{ $order->orderItems->count() }}</td>
                <td class="text-right">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                <td class="text-right font-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center" style="padding: 20px; color: #6b7280;">
                    No orders found
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
