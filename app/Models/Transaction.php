<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'payment_id', 'amount', 'currency_key', 'balance'];


    protected static function boot()
    {
        parent::boot();

        static::created(function (Transaction $transaction) {
            $exitsBalance = Transaction::query()->where('user_id', $transaction->user_id)->where('currency_key', $transaction->currency_key)->sum('amount');
            $transaction->update(['balance' => $exitsBalance]);
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
};
