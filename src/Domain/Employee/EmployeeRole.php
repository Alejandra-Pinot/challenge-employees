<?php

declare(strict_types=1);

namespace App\Domain\Employee;

use App\Domain\Shared\ValueObject\StringValueObject;

final class EmployeeRole extends StringValueObject
{
    protected function ensureIsValid(string $value): void
    {
        $allowed = ['Admin', 'Manager', 'Developer', 'HR', 'Intern'];

        if (!in_array($value, $allowed, true)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid role "%s". Allowed roles: %s', $value, implode(', ', $allowed))
            );
        }
    }
}
