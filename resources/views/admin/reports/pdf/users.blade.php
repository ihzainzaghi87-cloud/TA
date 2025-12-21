<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Users Report</title>
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
            border-bottom: 3px solid #7c3aed;
        }
        .header h1 {
            font-size: 24px;
            color: #6d28d9;
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
        }
        .summary-row {
            display: table-row;
        }
        .summary-item {
            display: table-cell;
            padding: 10px;
            text-align: center;
            border-right: 1px solid #e5e7eb;
            width: 50%;
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
            font-size: 18px;
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
            background-color: #6d28d9;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            border: 1px solid #5b21b6;
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
        .badge-admin { background: #fee2e2; color: #991b1b; }
        .badge-customer { background: #dbeafe; color: #1e40af; }
        
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
        <h1>Users Report</h1>
        <div class="subtitle">Customer List with Points and Order History</div>
        <div class="date">Generated on {{ now()->format('l, d F Y H:i:s') }}</div>
    </div>

    @if(request('start_date') || request('end_date') || request('role'))
    <div class="filter-info">
        <div class="title">Applied Filters</div>
        <div class="filter-grid">
            <div class="filter-row">
                @if(request('start_date') || request('end_date'))
                <div class="filter-item">
                    <span class="filter-label">Registration Date</span>
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
                
                @if(request('role'))
                <div class="filter-item">
                    <span class="filter-label">Role</span>
                    <span class="filter-value">{{ ucfirst(request('role')) }}</span>
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
                    <span class="label">Total Users</span>
                    <span class="value">{{ number_format($summary['total_users']) }}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Total Points</span>
                    <span class="value">{{ number_format($summary['total_points']) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 20%;">Name</th>
                <th style="width: 20%;">Email</th>
                <th style="width: 12%;">Phone</th>
                <th style="width: 10%;">Role</th>
                <th style="width: 10%;" class="text-right">Points</th>
                <th style="width: 8%;" class="text-center">Orders</th>
                <th style="width: 15%;" class="text-right">Total Spent</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $user)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="font-bold">{{ $user->name }}</td>
                <td class="text-sm">{{ $user->email }}</td>
                <td>{{ $user->phone ?? '-' }}</td>
                <td>
                    <span class="badge badge-{{ $user->role }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td class="text-right font-bold">{{ number_format($user->userPoint->total_points ?? 0) }}</td>
                <td class="text-center">{{ $user->orders->count() }}</td>
                <td class="text-right">Rp {{ number_format($user->orders->sum('total'), 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 20px; color: #6b7280;">
                    No users found
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
