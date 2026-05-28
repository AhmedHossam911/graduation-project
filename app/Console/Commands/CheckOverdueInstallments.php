<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Financial\Installment;

/**
 * Console Command: installments:check-overdue
 * 
 * Scheduled task that automatically scans for unpaid loan installments
 * whose due date has passed, and updates their status to 'overdue'.
 * This is crucial for accurate financial reporting and penalty calculations.
 */
class CheckOverdueInstallments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'installments:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check overdue loan installments and update their status.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Bulk update the status to 'overdue' for any installment that is still 'unpaid'
        // but its scheduled due date is strictly in the past.
        $updatedCount = Installment::where('status', 'unpaid')
            ->where('due_date', '<', $now)
            ->update(['status' => 'overdue']);

        $this->info("Checked loan installments successfully. Marked {$updatedCount} installments as overdue.");
    }
}
