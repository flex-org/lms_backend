<?php

namespace App\Modules\V1\Billing\Domain\Enums;

enum PendingChangeStatus: string
{
    case PENDING = 'pending';
    case APPLIED = 'applied';
    case CANCELLED = 'cancelled';
}

