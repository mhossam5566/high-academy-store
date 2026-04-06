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
        $query = Order::where('shipping_method', "2")->with(['orderDetails.products', 'governorate'])
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
            'Package_Serial',
            'Description',
            'Total_Weight',
            'Package_volume',
            'COD_Value',
            'Item_Special_Notes',
            'Customer_Name',
            'Mobile_No',
            'Street',
            'City',
            'Package_Ref_Number',
            'Merchant_Name',
            'Warehouse_Name',
            'HasPOD',
            'SellerName',
            'Post_Id',
        ];
    }

    public function map($order): array
    {
        // Total weight = sum of (product.weight * quantity) for all order details
        $totalWeight = $order->orderDetails->sum(function ($detail) {
            $productWeight = optional($detail->products)->weight ?? 200;
            return $productWeight * ($detail->amout ?? 1);
        });

        // Get governorate English name in uppercase from the relation
        $governorate = $order->governorate
            ? strtoupper($order->governorate->governorate_name_en)
            : '';

        // Primary phone: ensure starts with 0
        $mobile = $order->mobile ?? '';
        if (!empty($mobile) && !str_starts_with($mobile, '0')) {
            $mobile = '0' . $mobile;
        }

        // Notes: base note + backup number if exists
        $notes = 'يسلم لاي شخص دون الرجوع للرقم القومي \ او يتم الاتصال بالراسل في حال تعذر التسليم';
        if (!empty($order->temp_mobile)) {
            $notes .= ' / رقم احتياطي: ' . $order->temp_mobile;
        }

        return [
            '',           // Package_Serial
            'books',      // Description
            $totalWeight, // Total_Weight
            'small',      // Package_volume
            '',           // COD_Value
            $notes,       // Item_Special_Notes
            $order->name ?? '',    // Customer_Name
            $mobile,               // Mobile_No
            $order->address2 ?? '', // Street
            $governorate,          // City
            '',   // Package_Ref_Number
            '',   // Merchant_Name
            '',   // Warehouse_Name
            '', // HasPOD
            '',   // SellerName
            '',   // Post_Id
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
