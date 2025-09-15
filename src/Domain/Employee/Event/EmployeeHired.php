<?php
declare(strict_types=1);

namespace App\Domain\Employee\Event;

use App\Domain\Shared\ValueObject\EmployeeId;
use App\Domain\Employee\EmployeeName;
use App\Domain\Shared\DomainEvent;

final class EmployeeHired implements DomainEvent
{
    public function __construct(
        private readonly EmployeeId $employeeId,
        private readonly EmployeeName $name,
        private readonly string $email,
        private readonly string $department,
        private readonly string $role,
        private readonly \DateTimeImmutable $hiredAt,
        private readonly \DateTimeImmutable $occurredOn = new \DateTimeImmutable()
    ) {}

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function employeeId(): EmployeeId { return $this->employeeId; }
    public function name(): EmployeeName { return $this->name; }
    public function email(): string { return $this->email; }
    public function department(): string { return $this->department; }
    public function role(): string { return $this->role; }
    public function hiredAt(): \DateTimeImmutable { return $this->hiredAt; }
}