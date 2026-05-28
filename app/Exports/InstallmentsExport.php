<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

/**
 * Handles the generation of Excel reports for loan installments.
 * Maps individual installment records to their parent loans and translates their payment statuses.
 */
class InstallmentsExport implements FromQuery, WithHeadings, WithMapping, WithStyles
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

    public function headings(): array
    {
        return [
            'رقم القسط',
            'رقم القرض',
            'اسم العضو',
            'المبلغ',
            'تاريخ الاستحقاق',
            'الحالة',
        ];
    }

    public function map($installment): array
    {
        $memberName = $installment->loan->membership->member->full_name ?? '-';

        $statusLabel = match($installment->status) {
            'paid' => 'تم الدفع',
            'unpaid' => 'غير مدفوع',
            'overdue' => 'متأخر',
            default => $installment->status,
        };

        return [
            $installment->id,
            'LOAN-' . $installment->loan_id,
            $memberName,
            number_format($installment->amount, 2) . ' ج.م',
            Carbon::parse($installment->due_date)->format('Y-m-d'),
            $statusLabel,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'EEF7FF'],
                ],
            ],
        ];
    }
}
