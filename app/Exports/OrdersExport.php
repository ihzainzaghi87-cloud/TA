<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Order::with(['user', 'orderItems']);

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('created_at', '>=', $this->filters['start_date']);
        }
        if (!empty($this->filters['end_date'])) {
            $query->whereDate('created_at', '<=', $this->filters['end_date']);
        }
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if (!empty($this->filters['payment_status'])) {
            $query->where('payment_status', $this->filters['payment_status']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Order Number',
            'Customer Name',
            'Customer Email',
            'Order Date',
            'Status',
            'Payment Status',
            'Items Count',
            'Subtotal',
            'Shipping Cost',
            'Total',
            'Points Used',
            'Points Earned',
            'Courier',
            'Service',
            'Tracking Number',
            'Shipped At',
            'Delivered At',
        ];
    }

    public function map($order): array
    {
        return [
            $order->order_number,
            $order->user->name ?? '-',
            $order->user->email ?? '-',
            $order->created_at->format('Y-m-d H:i:s'),
            $order->status,
            $order->payment_status,
            $order->orderItems->count(),
            number_format($order->subtotal, 2),
            number_format($order->shipping_cost, 2),
            number_format($order->total, 2),
            $order->total_points_used,
            $order->points_earned,
            $order->courier ?? '-',
            $order->service ?? '-',
            $order->tracking_number ?? '-',
            $order->shipped_at ? $order->shipped_at->format('Y-m-d H:i:s') : '-',
            $order->delivered_at ? $order->delivered_at->format('Y-m-d H:i:s') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Orders Report';
    }
}
