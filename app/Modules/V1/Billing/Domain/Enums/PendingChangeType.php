<?php

namespace App\Modules\V1\Billing\Domain\Enums;

enum PendingChangeType: string
{
    case ADD_FEATURE = 'add_feature';
    case INCREASE_STORAGE = 'increase_storage';
    case INCREASE_CAPACITY = 'increase_capacity';
    case ENABLE_MOBILE = 'enable_mobile';

    public function label(): string
    {
        return __("enums.pending_change_type.{$this->value}");
    }
}

