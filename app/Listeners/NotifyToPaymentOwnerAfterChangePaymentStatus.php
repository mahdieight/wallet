<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;


class NotifyToPaymentOwnerAfterChangePaymentStatus implements ShouldQueue
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
    public function handle(object $event): void
    {
        $message = $event->payment->user->name . " Dear, Payment " . $event->payment->unique_id . ($event->status->value == PaymentStatusEnum::APPROVED->value ? ' Approved.' : ' Rejected.');
        Mail::raw($message, function ($message) use ($event) {
            $message->from('john@johndoe.com', 'John Doe');
            $message->sender('john@johndoe.com', 'John Doe');
            $message->to($event->payment->user->email, $event->payment->user->email);
            $message->subject('Test Mail');
        });
    }
}
