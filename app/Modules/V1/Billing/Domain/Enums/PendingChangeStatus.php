<?php

namespace App\Modules\V1\Billing\Domain\Enums;

enum PendingChangeStatus: string
{
    case PENDING = 'pending';
    case APPLIED = 'applied';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return __("enums.pending_change_status.{$this->value}");
    }
}

