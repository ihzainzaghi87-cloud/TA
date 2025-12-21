<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 40px;
        }
        
        .header {
            margin-bottom: 40px;
            border-bottom: 3px solid #FAD470;
            padding-bottom: 20px;
        }
        
        .company-info {
            float: left;
            width: 50%;
        }
        
        .company-info h1 {
            font-size: 28px;
            color: #1f2937;
            margin-bottom: 5px;
        }
        
        .company-info p {
            color: #6b7280;
            font-size: 11px;
            line-height: 1.5;
        }
        
        .invoice-info {
            float: right;
            width: 45%;
            text-align: right;
        }
        
        .invoice-title {
            font-size: 32px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }
        
        .invoice-number {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        
        .invoice-date {
            font-size: 11px;
            color: #9ca3af;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f3f4f6;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .info-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }
        
        .info-box {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        
        .info-box h3 {
            font-size: 12px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }
        
        .info-box p {
            font-size: 11px;
            color: #4b5563;
            margin-bottom: 5px;
        }
        
        .info-box .label {
            color: #6b7280;
            display: inline-block;
            width: 100px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            margin-top: 5px;
        }
        
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-shipped { background: #e9d5ff; color: #6b21a8; }
        .status-delivered { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        thead th {
            background: #1f2937;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
        }
        
        tbody td {
            padding: 12px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        
        tbody tr:last-child td {
            border-bottom: 2px solid #1f2937;
        }
        
        .product-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 3px;
        }
        
        .product-variant {
            font-size: 10px;
            color: #6b7280;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .summary-table {
            width: 350px;
            float: right;
            margin-top: 20px;
        }
        
        .summary-table table {
            width: 100%;
            margin-bottom: 0;
        }
        
        .summary-table td {
            padding: 8px;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .summary-table .total-row td {
            padding-top: 12px;
            border-top: 2px solid #1f2937;
            border-bottom: none;
            font-weight: bold;
            font-size: 14px;
        }
        
        .summary-table .discount-row td {
            color: #059669;
        }
        
        .notes {
            clear: both;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        
        .notes h4 {
            font-size: 12px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 8px;
        }
        
        .notes p {
            font-size: 10px;
            color: #6b7280;
            line-height: 1.6;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #FAD470;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
        
        .highlight-box {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            padding: 12px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .highlight-box p {
            font-size: 10px;
            color: #92400e;
            margin: 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header clearfix">
        <div class="company-info">
            <h1>{{ $companyName }}</h1>
            <p>
                {{ $companyAddress }}<br>
                Phone: {{ $companyPhone }}<br>
                Email: {{ $companyEmail }}
            </p>
        </div>
        
        <div class="invoice-info">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">#{{ $order->order_number }}</div>
            <div class="invoice-date">{{ $order->created_at->format('d F Y, H:i') }}</div>
            <span class="status-badge status-{{ strtolower($order->status) }}">
                {{ $order->status }}
            </span>
        </div>
    </div>

    <!-- Customer & Shipping Info -->
    <div class="section">
        <div class="info-grid">
            <div class="info-column">
                <div class="info-box">
                    <h3>Bill To:</h3>
                    <p><strong>{{ $order->user->name }}</strong></p>
                    <p>{{ $order->user->email }}</p>
                    <p>{{ $order->user->phone_number ?? '-' }}</p>
                </div>
            </div>
            
            <div class="info-column">
                <div class="info-box">
                    <h3>Ship To:</h3>
                    @if($order->shippingAddress)
                    <p><strong>{{ $order->shippingAddress->recipient_name }}</strong></p>
                    <p>{{ $order->shippingAddress->phone }}</p>
                    <p>{{ $order->shippingAddress->full_address }}</p>
                    <!-- <p>{{ $order->shippingAddress->city_name ?? '' }}, {{ $order->shippingAddress->province_name ?? '' }}</p> -->
                    <p>{{ $order->shippingAddress->postal_code }}</p>
                    @else
                    <p>No shipping address available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Shipping Information -->
    @if($order->courier)
    <div class="section">
        <div class="info-box">
            <h3>Shipping Information:</h3>
            <p>
                <span class="label">Courier:</span>
                <strong>{{ strtoupper($order->courier) }} - {{ $order->service ?? '-' }}</strong>
            </p>
            @if($order->tracking_number)
            <p>
                <span class="label">Tracking Number:</span>
                <strong style="font-family: monospace;">{{ $order->tracking_number }}</strong>
            </p>
            @endif
            @if($order->delivered_at)
            <p>
                <span class="label">Delivered At:</span>
                {{ \Carbon\Carbon::parse($order->delivered_at)->format('d F Y, H:i') }}
            </p>
            @endif
        </div>
    </div>
    @endif

    <!-- Order Items -->
    <div class="section">
        <div class="section-title">Order Items</div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 45%;">Product</th>
                    <th style="width: 15%;" class="text-center">Quantity</th>
                    <th style="width: 17%;" class="text-right">Unit Price</th>
                    <th style="width: 18%;" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div class="product-name">{{ $item->variation->product->name ?? 'Product not available' }}</div>
                        <div class="product-variant">
                            Variant: {{ $item->variation->color ?? '-' }} / {{ $item->variation->size ?? '-' }}
                        </div>
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right"><strong>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Payment Summary -->
    <div class="summary-table">
        <table>
            <tr>
                <td>Subtotal ({{ $order->orderItems->sum('quantity') }} items):</td>
                <td class="text-right">Rp {{ number_format($order->subtotal ?? $order->orderItems->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Shipping Cost:</td>
                <td class="text-right">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</td>
            </tr>
            @if($order->points_used > 0)
            <tr class="discount-row">
                <td>Points Used:</td>
                <td class="text-right">- Rp {{ number_format($order->points_used, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td>Total:</td>
                <td class="text-right" style="color: #FAD470;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Points Earned -->
    @if($order->pointTransactions && $order->pointTransactions->where('type', 'earned')->first())
    <div class="highlight-box clearfix">
        <p>
            <strong>🎉 Congratulations!</strong> You earned 
            <strong>{{ $order->pointTransactions->where('type', 'earned')->first()->points }} points</strong> 
            from this order!
        </p>
    </div>
    @endif

    <!-- Notes -->
    <div class="notes">
        <h4>Notes:</h4>
        <p>
            • This is a computer-generated invoice and does not require a signature.<br>
            • Please keep this invoice for your records.<br>
            • If you have any questions, please contact our customer service at {{ $companyEmail }}<br>
            • Thank you for shopping with us!
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>{{ $companyName }} | {{ $companyAddress }}</p>
        <p>Phone: {{ $companyPhone }} | Email: {{ $companyEmail }}</p>
        <p style="margin-top: 10px;">Invoice generated on {{ now()->format('d F Y, H:i:s') }}</p>
    </div>
</body>
</html>
