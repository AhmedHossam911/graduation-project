<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MembersDistributionExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $departments;

    public function __construct($departments)
    {
        $this->departments = $departments;
    }

    public function collection()
    {
        return $this->departments;
    }

    public function headings(): array
    {
        return [
            'اسم الكلية / الإدارة',
            'عدد الأعضاء',
        ];
    }

    public function map($department): array
    {
        return [
            $department->name,
            $department->members_count,
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
