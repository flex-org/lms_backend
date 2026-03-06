<?php

namespace App\Modules\V1\Platforms\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class DomainName
{
    public string $value;

    public function __construct(string $value)
    {
        $trimmed = strtolower(trim($value));

        if ($trimmed === '' || strlen($trimmed) > 255) {
            throw new InvalidArgumentException('Domain name must be between 1 and 255 characters.');
        }

        if (strlen($trimmed) > 1 && ! preg_match('/^[a-z0-9][a-z0-9-]*[a-z0-9]$/', $trimmed)) {
            throw new InvalidArgumentException('Domain name contains invalid characters.');
        }

        $this->value = $trimmed;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
