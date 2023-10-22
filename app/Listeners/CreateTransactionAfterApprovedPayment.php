<?php

namespace App\Listeners;

use App\Events\Payment\PaymentApproved;
use App\Models\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateTransactionAfterApprovedPayment
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentApproved $event): void
    {
        Transaction::create([
            'user_id' => $event->payment->user_id,
            'payment_id' => $event->payment->id,
            'amount' => $event->payment->amount,
            'currency_key' => $event->payment->currency_key,
        ]);
    }
}
