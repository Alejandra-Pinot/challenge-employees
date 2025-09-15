<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Shared\ValueObject\StringValueObject;

class EventTitle extends StringValueObject
{
    protected function ensureIsValid(string $value): void
    {
        if (empty(trim($value))) {
            throw new \InvalidArgumentException('Event title cannot be empty');
        }

        if (strlen($value) > 255) {
            throw new \InvalidArgumentException('Event title cannot be longer than 255 characters');
        }
    }
} 