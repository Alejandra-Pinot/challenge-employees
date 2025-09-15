<?php

declare(strict_types=1);

namespace App\Domain\Employee;

use App\Domain\Shared\ValueObject\StringValueObject;

final class EmployeeName extends StringValueObject
{
    protected function ensureIsValid(string $value): void
    {
        $trim = trim($value);
        if ($trim === '') {
            throw new \InvalidArgumentException('Employee name cannot be empty');
        }
        if (mb_strlen($trim) > 255) {
            throw new \InvalidArgumentException('Employee name is too long');
        }
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }
}
