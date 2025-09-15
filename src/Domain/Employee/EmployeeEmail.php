<?php

declare(strict_types=1);

namespace App\Domain\Employee;

use App\Domain\Shared\ValueObject\StringValueObject;

final class EmployeeEmail extends StringValueObject
{
    protected function ensureIsValid(string $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid employee email format');
        }
    }
}
