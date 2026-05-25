<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LoansExport implements FromQuery, WithMapping, WithHeadings
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function map($loan): array
    {
        $status = match ($loan->status) {
            'pending' => 'قيد المراجعة',
            'approved' => 'معتمد',
            'active' => 'نشط',
            'completed' => 'مكتمل',
            'rejected' => 'مرفوض',
            default => '---',
        };

        return [
            $loan->id,
            $loan->membership->membership_number ?? '---',
            $loan->membership->member->full_name ?? '---',
            number_format($loan->total_amount, 2) . ' ج.م',
            $loan->months . ' شهر',
            number_format($loan->installment_amount, 2) . ' ج.م',
            $status,
            $loan->created_at ? $loan->created_at->format('Y-m-d') : '---',
        ];
    }

    public function headings(): array
    {
        return [
            'رقم القرض',
            'رقم العضوية',
            'اسم العضو',
            'إجمالي القرض',
            'المدة',
            'قيمة القسط',
            'الحالة',
            'تاريخ الطلب',
        ];
    }
}
