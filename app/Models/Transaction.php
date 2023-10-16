<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'payment_id', 'amount', 'currency', 'balance'];


    protected static function boot()
    {
        parent::boot();

        static::created(function (Transaction $transaction) {
            $exitsBalance = Transaction::query()->where('user_id', $transaction->user_id)->where('currency', $transaction->currency)->sum('amount');
            $transaction->update(['balance' => $exitsBalance,]);
        });
    }
}
