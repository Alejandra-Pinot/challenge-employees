<?php
declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Shared\ValueObject\Uuid;

final class EventId extends Uuid
{
    public static function generate(): string
    {
        return parent::generate();
    }

    public static function new(): self
    {
        return new self(self::generate());
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }
}
