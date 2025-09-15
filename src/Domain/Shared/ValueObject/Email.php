<?php
declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

final class Email extends StringValueObject
{
    public static function fromString(string $value): self
    {
        return new self($value);
    }

    protected function ensureIsValid(string $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(sprintf('Invalid email: %s', $value));
        }
    }
}