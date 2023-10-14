<?php

namespace App\Listeners;

use App\Events\PaymentRejected;
use App\Jobs\sendRejectPaymentEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRejectedPaymentEmail
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
    public function handle(PaymentRejected $event): void
    {
        sendRejectPaymentEmail::dispatch($event->payment , $event->message);
    }
}
