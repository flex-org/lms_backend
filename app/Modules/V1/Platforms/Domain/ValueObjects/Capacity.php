<?php

namespace App\Modules\V1\Platforms\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class Capacity
{
    private const MIN_STUDENTS = 100;

    public function __construct(public int $students)
    {
        if ($students < self::MIN_STUDENTS) {
            throw new InvalidArgumentException(
                "Capacity must be at least " . self::MIN_STUDENTS . " students."
            );
        }
    }

    public function equals(self $other): bool
    {
        return $this->students === $other->students;
    }
}
