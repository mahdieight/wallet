<?php

namespace App\Console\Commands;

use App\Enums\Payment\PaymentStatusEnum;
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
        Payment::where([
            ['status', PaymentStatusEnum::PENDING->value],
            ['created_at', '<=', now()->subMinutes(1440)]
        ])->chunk(50, function ($payments) {
            $payments->delete();
        });


        $this->info('Payment Successfully Destroy');
    }
}
