<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
        $transactionBalance = 0;
        $balances = DB::table('transactions')
            ->select('currency', DB::raw('SUM(amount) as total_amount'))
            ->where('user_id', $event->transaction->user_id)
            ->groupBy('currency')
            ->get();

        $balanceData = ['salam' => 550];
        foreach ($balances as $balance) {
            if ($event->transaction->currency == $balance->currency) {
                $transactionBalance = $balance->total_amount;
            }
            $data[$balance->currency] =  $balance->total_amount;
        }

        $balanceData = json_encode($balanceData);

        $event->transaction->user->update(['balances' => $balanceData]);
        $event->transaction->update(['balance' => $transactionBalance]);
    }
}
