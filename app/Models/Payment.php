<?php

namespace App\Models;

use App\Enums\Payment\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'user_id',
        'status',
        'amount',
        'currency_key'
    ];

    protected $hidden = ['id'];

    /**
     * Write code on Method
     *
     * @return response()
     */
    protected $casts = [
        'status' => PaymentStatusEnum::class
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    protected static function booted()
    {
        static::creating(function ($payment) {
            $payment->unique_id = uniqid();
        });
    }


    public function getRouteKeyName()
    {
        return 'unique_id';
    }
}
