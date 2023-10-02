<?php

namespace App\Models;

use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'amount',
        'priceunit',
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
}
