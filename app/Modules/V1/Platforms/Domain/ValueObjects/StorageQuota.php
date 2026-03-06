<?php

namespace App\Modules\V1\Platforms\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class StorageQuota
{
    private const MIN_MB = 50;

    public function __construct(public int $megabytes)
    {
        if ($megabytes < self::MIN_MB) {
            throw new InvalidArgumentException(
                "Storage quota must be at least " . self::MIN_MB . " MB."
            );
        }
    }

    public function toGigabytes(): float
    {
        return round($this->megabytes / 1024, 2);
    }

    public function equals(self $other): bool
    {
        return $this->megabytes === $other->megabytes;
    }
}
