<?php

namespace App\Exports;

use App\Models\Services\Claim;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClaimsExport implements FromQuery, WithMapping, WithHeadings
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

    public function map($claim): array
    {
        $status = match ($claim->status) {
            'pending' => 'بانتظار الأعتماد',
            'approved' => 'بانتظار التسوية',
            'paid' => 'تم الصرف',
            'rejected' => 'مرفوض',
            default => '---',
        };

        $type = Claim::CLAIM_TYPES[$claim->type] ?? '---';

        return [
            $claim->id,
            $claim->membership->membership_number ?? '---',
            $claim->membership->member->full_name ?? ($claim->membership->member->name ?? '---'),
            $type,
            number_format($claim->amount, 2) . ' ج.م',
            $status,
            $claim->created_at ? $claim->created_at->format('Y-m-d') : '---',
        ];
    }

    public function headings(): array
    {
        return [
            'رقم المطالبة',
            'رقم العضوية',
            'اسم العضو',
            'نوع المطالبة',
            'المبلغ',
            'الحالة',
            'تاريخ الطلب',
        ];
    }
}
