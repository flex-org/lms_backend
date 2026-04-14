<?php

namespace App\Modules\V1\Billing\Domain\Enums;

enum InvoiceType: string
{
    case MONTHLY = 'monthly';
    case PRORATION = 'proration';

    public function label(): string
    {
        return __("enums.invoice_type.{$this->value}");
    }
}

