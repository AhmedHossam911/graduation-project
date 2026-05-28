<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

/**
 * Handles the generation of Excel reports for the system audit logs.
 * Allows administrators to export a traceable history of user activities across all system tables.
 */
class AuditLogsExport implements FromQuery, WithHeadings, WithMapping, WithStyles
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
            'رقم السجل',
            'المستخدم',
            'العملية',
            'الجدول',
            'التاريخ والوقت',
            'عنوان IP',
        ];
    }

    public function map($log): array
    {
        return [
            $log->id,
            $log->user->name ?? 'غير معروف',
            $log->action,
            $log->table_name,
            Carbon::parse($log->created_at)->format('Y-m-d H:i:s'),
            $log->ip_address ?? '-',
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
