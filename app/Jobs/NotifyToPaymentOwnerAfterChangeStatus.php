<?php

namespace App\Jobs;

use App\Enums\Payment\PaymentStatusEnum;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
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
    public function __construct(
        public Payment $payment,
        public PaymentStatusEnum $status
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $message = $this->payment->user->name . " Dear, Payment " . $this->payment->unique_id . ($this->status == PaymentStatusEnum::APPROVED->value ? ' Approved.' : ' Rejected.');
        Mail::raw($message, function ($message) {
            $message->from('john@johndoe.com', 'John Doe');
            $message->sender('john@johndoe.com', 'John Doe');
            $message->to($this->payment->user->email, $this->payment->user->email);
            $message->subject('Test Mail');
        });
    }
}
