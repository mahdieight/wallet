<?php

namespace App\Listeners;

use App\Models\Transaction;
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
            ->select('currency_key', DB::raw('SUM(amount) as total_amount'))
            ->where('user_id', $event->payment->user->id)
            ->groupBy('currency_key')
            ->pluck('total_amount', 'currency_key');
        $event->payment->user->update(['balance' => $balances->toJson()]);
    }
}
