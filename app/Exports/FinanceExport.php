<?php

namespace App\Exports;

use App\Models\Financial\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

/**
 * Handles the generation of Excel reports for all general financial transactions (inflows/outflows).
 * Enriches the raw transaction data with human-readable labels for payment categories and methods.
 */
class FinanceExport implements FromQuery, WithHeadings, WithMapping, WithStyles
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
            'رقم الحركة',
            'اسم العضو',
            'التاريخ والوقت',
            'بند الحركة',
            'المبلغ',
            'طريقة الدفع',
            'الحالة',
            'بيان الحركة',
        ];
    }

    public function map($transaction): array
    {
        $memberName = '-';
        if ($transaction->membership && $transaction->membership->member) {
            $memberName = $transaction->membership->member->user->name ?? '-';
        }

        $categoryLabel = Transaction::CATEGORY_LABELS[$transaction->category] ?? $transaction->category ?? '-';
        $methodLabel = Transaction::METHOD_LABELS[$transaction->method] ?? $transaction->method ?? '-';
        $typeLabel = $transaction->type === 'IN' ? 'إيراد' : 'مصروف';

        $date = Carbon::parse($transaction->created_at)
            ->locale('ar')
            ->translatedFormat('d F Y - h:i A');

        return [
            'TRX-' . $transaction->id,
            $memberName,
            $date,
            $categoryLabel,
            number_format($transaction->amount, 2) . ' ج.م',
            $methodLabel,
            $typeLabel,
            $transaction->description ?? '-',
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
