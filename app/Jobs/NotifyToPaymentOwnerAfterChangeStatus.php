<?php

namespace App\Jobs;

use App\Enums\Payment\PaymentStatusEnum;
use App\Events\Payment\PaymentApproved;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifyToPaymentOwnerAfterChangeStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(PaymentApproved $event): void
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
