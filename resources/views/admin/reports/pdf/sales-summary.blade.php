<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Summary Report</title>
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
            border-bottom: 3px solid #dc2626;
        }
        .header h1 {
            font-size: 24px;
            color: #b91c1c;
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
            background: #fef2f2;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #fecaca;
            text-align: center;
        }
        .filter-info .title {
            font-size: 10px;
            font-weight: 600;
            color: #b91c1c;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .filter-info .period {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
        }
        .filter-info .period-label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .filter-info .date-range {
            display: inline-block;
            background: white;
            padding: 8px 15px;
            border-radius: 6px;
            border: 1px solid #fca5a5;
            margin-top: 5px;
        }
        .filter-info .date-item {
            display: inline-block;
            margin: 0 10px;
        }
        .filter-info .date-separator {
            color: #dc2626;
            font-weight: bold;
            margin: 0 5px;
        }
        
        /* Summary Cards */
        .summary-cards {
            margin-bottom: 25px;
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
            padding: 15px;
            text-align: center;
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            width: 25%;
        }
        .summary-item .label {
            display: block;
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .summary-item .value {
            display: block;
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
        }
        
        /* Section Title */
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            margin-top: 25px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #b91c1c;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            border: 1px solid #991b1b;
        }
        td {
            padding: 8px;
            border: 1px solid #e5e7eb;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        /* Progress Bar */
        .progress-bar {
            width: 100%;
            height: 10px;
            background: #e5e7eb;
            border-radius: 5px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(to right, #dc2626, #ec4899);
        }
        
        /* Badges */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
        }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-processing { background: #dbeafe; color: #1e40af; }
        .badge-shipped { background: #e9d5ff; color: #6b21a8; }
        .badge-delivered { background: #d1fae5; color: #065f46; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }
        .badge-paid { background: #d1fae5; color: #065f46; }
        .badge-failed { background: #fee2e2; color: #991b1b; }
        
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
        <h1>Sales Summary Report</h1>
        <div class="subtitle">Complete Sales Performance Overview</div>
        <div class="date">Generated on {{ now()->format('l, d F Y H:i:s') }}</div>
    </div>

    <!-- Filter Information - Date Range Period -->
    <div class="filter-info">
        <div class="title">Report Period</div>
        <div class="period-label">Sales Data Range</div>
        <div class="date-range">
            <div class="date-item">
                <strong>From:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }}
            </div>
            <span class="date-separator">-</span>
            <div class="date-item">
                <strong>To:</strong> {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
            </div>
        </div>
        <div style="margin-top: 8px; font-size: 9px; color: #6b7280;">
            Duration: {{ \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1 }} days
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
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
                    <span class="label">Avg Order Value</span>
                    <span class="value">Rp {{ number_format($summary['average_order_value'], 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders by Status -->
    <div class="section-title">Orders by Status</div>
    <table>
        <thead>
            <tr>
                <th style="width: 25%;">Status</th>
                <th style="width: 20%;" class="text-center">Order Count</th>
                <th style="width: 25%;" class="text-right">Total Revenue</th>
                <th style="width: 30%;">Percentage</th>
            </tr>
        </thead>
        <tbody>
            @forelse($summary['by_status'] as $status)
            <tr>
                <td>
                    <span class="badge badge-{{ strtolower($status->status) }}">
                        {{ $status->status }}
                    </span>
                </td>
                <td class="text-center font-bold">{{ number_format($status->count) }}</td>
                <td class="text-right">Rp {{ number_format($status->total, 0, ',', '.') }}</td>
                <td>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $summary['total_orders'] > 0 ? ($status->count / $summary['total_orders'] * 100) : 0 }}%;"></div>
                    </div>
                    <div class="text-sm text-center" style="margin-top: 3px;">
                        {{ $summary['total_orders'] > 0 ? number_format($status->count / $summary['total_orders'] * 100, 1) : 0 }}%
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center" style="padding: 15px; color: #6b7280;">
                    No data available
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Orders by Payment Status -->
    <div class="section-title">Orders by Payment Status</div>
    <table>
        <thead>
            <tr>
                <th style="width: 25%;">Payment Status</th>
                <th style="width: 20%;" class="text-center">Order Count</th>
                <th style="width: 25%;" class="text-right">Total Revenue</th>
                <th style="width: 30%;">Percentage</th>
            </tr>
        </thead>
        <tbody>
            @forelse($summary['by_payment'] as $payment)
            <tr>
                <td>
                    <span class="badge badge-{{ strtolower($payment->payment_status) }}">
                        {{ $payment->payment_status }}
                    </span>
                </td>
                <td class="text-center font-bold">{{ number_format($payment->count) }}</td>
                <td class="text-right">Rp {{ number_format($payment->total, 0, ',', '.') }}</td>
                <td>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $summary['total_orders'] > 0 ? ($payment->count / $summary['total_orders'] * 100) : 0 }}%;"></div>
                    </div>
                    <div class="text-sm text-center" style="margin-top: 3px;">
                        {{ $summary['total_orders'] > 0 ? number_format($payment->count / $summary['total_orders'] * 100, 1) : 0 }}%
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center" style="padding: 15px; color: #6b7280;">
                    No data available
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Additional Statistics (Optional) -->
    @if($summary['total_orders'] > 0)
    <div class="section-title">Performance Insights</div>
    <div style="background: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb;">
        <div style="margin-bottom: 10px;">
            <strong style="color: #1f2937;">Daily Average:</strong>
            <span style="color: #6b7280;">
                {{ number_format($summary['total_orders'] / (\Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1), 1) }} orders/day
            </span>
        </div>
        <div style="margin-bottom: 10px;">
            <strong style="color: #1f2937;">Revenue per Order:</strong>
            <span style="color: #6b7280;">
                Rp {{ number_format($summary['average_order_value'], 0, ',', '.') }}
            </span>
        </div>
        <div>
            <strong style="color: #1f2937;">Net Revenue:</strong>
            <span style="color: #059669; font-weight: 600;">
                Rp {{ number_format($summary['total_revenue'] - $summary['total_shipping'], 0, ',', '.') }}
            </span>
            <span style="font-size: 9px; color: #6b7280;">(after shipping costs)</span>
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div>Fashion E-Commerce Report System | This is a computer-generated document</div>
        <div>Page generated at {{ now()->format('Y-m-d H:i:s') }}</div>
    </div>
</body>
</html>
