<?php

declare(strict_types=1);

namespace App\Domain\Employee;

use App\Domain\Shared\ValueObject\StringValueObject;

final class DepartmentName extends StringValueObject
{
    protected function ensureIsValid(string $value): void
    {
        if (empty(trim($value))) {
            throw new \InvalidArgumentException('Department name cannot be empty');
        }

        if (strlen($value) > 100) {
            throw new \InvalidArgumentException('Department name cannot exceed 100 characters');
        }
    }
}
