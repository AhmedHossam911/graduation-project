<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Financial\Installment;

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

        // Update status to 'overdue' if due_date has passed
        $updatedCount = Installment::where('status', 'unpaid')
            ->where('due_date', '<', $now)
            ->update(['status' => 'overdue']);

        $this->info("Checked loan installments successfully. Marked {$updatedCount} installments as overdue.");
    }
}
