<?php

namespace App\Modules\V1\Billing\Domain\Enums;

enum InvoiceStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case OVERDUE = 'overdue';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return __("enums.invoice_status.{$this->value}");
    }
}

