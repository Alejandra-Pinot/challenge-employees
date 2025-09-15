<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

abstract class StringValueObject
{
    private string $value;

    protected function __construct(string $value)
    {
        $this->ensureIsValid($value);
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(StringValueObject $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    abstract protected function ensureIsValid(string $value): void;
} 