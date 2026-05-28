<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Handles the generation of the Financial Position (الموقف المالي) Excel report.
 * Dynamically aggregates total revenues, expenses, net balance, and outstanding active loans.
 */
class FinancialPositionExport implements FromArray, WithHeadings, WithStyles
{
    protected $year;

    public function __construct($year = null)
    {
        $this->year = $year ?: date('Y');
    }

    public function array(): array
    {
        $totalRevenues = \App\Models\Financial\Transaction::where('type', 'IN')->whereYear('created_at', $this->year)->sum('amount');
        $totalExpenses = \App\Models\Financial\Transaction::where('type', 'OUT')->whereYear('created_at', $this->year)->sum('amount');
        $netBalance = $totalRevenues - $totalExpenses;
        $activeLoansBalance = \App\Models\Financial\Loan::whereIn('status', ['active', 'pending'])->whereYear('created_at', $this->year)->sum('total_amount') - \App\Models\Financial\Installment::where('status', 'paid')->whereYear('created_at', $this->year)->sum('amount');

        return [
            ['إجمالي الإيرادات', number_format($totalRevenues, 2) . ' ج.م'],
            ['إجمالي المصروفات', number_format($totalExpenses, 2) . ' ج.م'],
            ['صافي الرصيد', number_format($netBalance, 2) . ' ج.م'],
            ['رصيد القروض المستحقة', number_format($activeLoansBalance, 2) . ' ج.م'],
        ];
    }

    public function headings(): array
    {
        return [
            'البند',
            'القيمة',
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
