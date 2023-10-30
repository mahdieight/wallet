<?php

namespace App\Console\Commands;

use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemovePendingPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:remove-pending-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove pending payments after 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $payments = Payment::where('created_at' , '<=' , now()->subMinutes(1440));

        $payments->delete();

        $this->info('Payment Successfully Destroy');
    }
}
