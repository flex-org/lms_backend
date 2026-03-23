<?php

namespace App\Modules\V1\Billing\Domain\Enums;

enum InvoiceItemType: string
{
    case FEATURE = 'feature';
    case STORAGE = 'storage';
    case CAPACITY = 'capacity';
    case MOBILE_APP = 'mobile_app';
}

