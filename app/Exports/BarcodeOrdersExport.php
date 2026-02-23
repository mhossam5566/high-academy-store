<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BarcodeOrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $status;
    protected $limit;
    protected $shipping;
    protected $bookId;

    public function __construct($status = null, $limit = null, $shipping = null, $bookId = null)
    {
        $this->status = $status;
        $this->limit = $limit;
        $this->shipping = $shipping;
        $this->bookId = $bookId;
    }

    public function collection()
    {
        $query = Order::where('shipping_method', "2")->with(['orderDetails.products'])
            ->latest();

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->shipping && $this->shipping !== 'all') {
            $query->whereHas('shipping', function ($q) {
                $q->where('type', $this->shipping);
            });
        }

        if ($this->bookId) {
            $query->whereHas('orderDetails', function ($q) {
                $q->where('product_id', $this->bookId);
            });
        }

        if ($this->limit) {
            $query->limit($this->limit);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'سيريال نمبر',
            'وصف المحتوي',
            'الوزن',
            'حجم الشحنة',
            'المبلغ المراد تحصيله',
            'ملاحظات',
            'اسم العميل',
            'رقم المحمول',
            'العنوان بالتفصيل',
            'المحافظة',
            'الرقم المرجعي',
            'اسم المستودع',
            'الرغبة في علم الوصول',
            'اسم البائع',
        ];
    }

    public function map($order): array
    {
        // Total weight = sum of (product.weight * quantity) for all order details
        $totalWeight = $order->orderDetails->sum(function ($detail) {
            $productWeight = optional($detail->products)->weight ?? 200;
            return $productWeight * ($detail->amout ?? 1);
        });

        // Extract governorate from address (format: "محافظة - مدينة - ...")
        $parts = explode(' - ', $order->address ?? '');
        $governorate = $parts[0] ?? '';

        // Phone numbers (primary + backup)
        $phones = $order->mobile ?? '';
        if (!empty($order->temp_mobile)) {
            $phones .= ' / ' . $order->temp_mobile;
        }

        return [
            '',           // سيريال نمبر
            'كتب',        // وصف المحتوي
            $totalWeight, // الوزن
            'صغير',       // حجم الشحنة
            'لا يوجد',    // المبلغ المراد تحصيله
            'يسلم لاي شخص دون الرجوع للرقم القومي \ او يتم الاتصال بالراسل في حال تعذر التسليم', // ملاحظات
            $order->name ?? '',    // اسم العميل
            $phones,               // رقم المحمول
            $order->address ?? '', // العنوان بالتفصيل
            $governorate,          // المحافظة
            '',   // الرقم المرجعي
            '',   // اسم المستودع
            'لا', // الرغبة في علم الوصول
            '',   // اسم البائع
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
