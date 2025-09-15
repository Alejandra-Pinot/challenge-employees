<?php

declare(strict_types=1);

namespace App\Domain\Employee;

enum EmployeeStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case VACATION = 'vacation';
    case TERMINATED = 'terminated';

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this === self::INACTIVE;
    }

    public function isOnVacation(): bool
    {
        return $this === self::VACATION;
    }

    public function isTerminated(): bool
    {
        return $this === self::TERMINATED;
    }
}