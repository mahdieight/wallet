<?php

namespace App\Enums;
use BenSampo\Enum\Enum;

final class PaymentStatusEnum extends Enum
{
    const Pending = 'pending';

    const Rejected = 'rejected';

    const Accepted = 'accepted';
}

