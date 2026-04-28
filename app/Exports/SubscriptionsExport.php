<?php

namespace App\Exports;

use App\Models\Services\Subscription;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubscriptionsExport implements FromQuery, WithMapping, WithHeadings
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        // Return the query object passed in constructor
        // Important: Maatwebsite Excel handles the get() internally
        return $this->query;
    }

    public function map($subscription): array
    {
        $status = match ($subscription->status) {
            'paid' => 'مسدد',
            'unpaid' => 'غير مسدد',
            'overdue' => 'متأخر',
            default => '---',
        };

        return [
            $subscription->membership->membership_number ?? '---',
            $subscription->membership->member->full_name ?? 'حدث خطأ',
            number_format($subscription->amount, 2) . ' ج.م',
            $status,
            $subscription->due_date ? $subscription->due_date->isoFormat('MMMM YYYY') : '---',
        ];
    }

    public function headings(): array
    {
        return [
            'رقم العضوية',
            'اسم العضو',
            'المبلغ',
            'الحالة',
            'الشهر',
        ];
    }
}
