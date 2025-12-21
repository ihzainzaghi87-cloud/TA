<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Product::with(['category', 'variations']);

        if (!empty($this->filters['category_id'])) {
            $query->where('category_id', $this->filters['category_id']);
        }
        if (isset($this->filters['is_active'])) {
            $query->where('is_active', $this->filters['is_active']);
        }
        if (isset($this->filters['is_reward'])) {
            $query->where('is_reward', $this->filters['is_reward']);
        }

        return $query->orderBy('name')->get();
    }

    public function headings(): array
    {
        return [
            'Product Name',
            'Category',
            'Description',
            'Price',
            'Point Price',
            'Active',
            'Reward Product',
            'Variations Count',
            'Total Stock',
            'Created At',
        ];
    }

    public function map($product): array
    {
        return [
            $product->name,
            $product->category->name ?? '-',
            strip_tags($product->description ?? '-'),
            number_format($product->price, 2),
            $product->point_price ?? 0,
            $product->is_active ? 'Yes' : 'No',
            $product->is_reward ? 'Yes' : 'No',
            $product->variations->count(),
            $product->variations->sum('stock'),
            $product->created_at->format('Y-m-d H:i:s'),
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
        return 'Products Report';
    }
}
