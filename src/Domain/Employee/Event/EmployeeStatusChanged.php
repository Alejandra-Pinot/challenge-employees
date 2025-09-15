<?php
declare(strict_types=1);

namespace App\Domain\Employee\Event;

use App\Domain\Shared\ValueObject\EmployeeId;
use App\Domain\Employee\EmployeeStatus;
use App\Domain\Shared\DomainEvent;

final class EmployeeStatusChanged implements DomainEvent
{
    public function __construct(
        private readonly EmployeeId $employeeId,
        private readonly EmployeeStatus $newStatus,
        private readonly \DateTimeImmutable $occurredOn = new \DateTimeImmutable()
    ) {}

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function employeeId(): EmployeeId { return $this->employeeId; }
    public function newStatus(): EmployeeStatus { return $this->newStatus; }
}