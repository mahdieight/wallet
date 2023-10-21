<?php

namespace App\Listeners;

use App\Models\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class UpdateUserBalance
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


        $balances = Transaction::query()
            ->select('currency', DB::raw('SUM(amount) as total_amount'))
            ->where('user_id', $event->payment->user->id)
            ->groupBy('currency')
            ->pluck('total_amount', 'currency');
        $event->payment->user->update(['balance' => $balances->toJson()]);
    }
}
