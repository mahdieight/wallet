<?php

namespace App\Enums\Payment;

use App\Enums\Enum;

enum PaymentStatusEnum: string
{
    use Enum;

    case PENDING = 'pending';
    case REJECTED = 'rejected';
    case APPROVED = 'approved';
}
