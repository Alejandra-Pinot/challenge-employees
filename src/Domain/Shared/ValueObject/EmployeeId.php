<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use Symfony\Component\Uid\Uuid;

final class EmployeeId extends StringValueObject
{
    public static function new(): self
    {
        return new self(Uuid::v4()->toRfc4122());
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    protected function ensureIsValid(string $value): void
    {
        if (!Uuid::isValid($value)) {
            throw new \InvalidArgumentException(sprintf('Invalid UUID: %s', $value));
        }
    }
}
