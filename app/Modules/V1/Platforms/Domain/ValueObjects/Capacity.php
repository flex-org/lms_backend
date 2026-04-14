<?php

namespace App\Modules\V1\Platforms\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class Capacity
{
    private const MIN_STUDENTS = 100;

    public function __construct(public int $students)
    {
        if ($students < self::MIN_STUDENTS) {
            throw new InvalidArgumentException(__('platform.capacity_min', ['min' => self::MIN_STUDENTS]));
        }
    }

    public function equals(self $other): bool
    {
        return $this->students === $other->students;
    }
}
