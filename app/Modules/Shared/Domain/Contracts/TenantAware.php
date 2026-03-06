<?php

namespace App\Modules\Shared\Domain\Contracts;

interface TenantAware
{
    public function getDomain(): string;
}
