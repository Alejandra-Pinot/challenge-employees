<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use Symfony\Component\Uid\Uuid as SymfonyUuid;

abstract class Uuid
{
    private string $value;

    protected function __construct(string $value)
    {
        $this->ensureIsValidUuid($value);
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Uuid $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    protected static function generate(): string
    {
        return SymfonyUuid::v4()->toRfc4122();
    }

    private function ensureIsValidUuid(string $value): void
    {
        if (!SymfonyUuid::isValid($value)) {
            throw new \InvalidArgumentException(sprintf('<%s> does not allow the invalid uuid <%s>.', static::class, $value));
        }
    }
} 