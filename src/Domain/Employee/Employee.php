<?php
declare(strict_types=1);

namespace App\Domain\Employee;

use App\Domain\Shared\AggregateRoot;
use App\Domain\Shared\ValueObject\EmployeeId;
use App\Domain\Employee\Event\EmployeeHired;
use App\Domain\Employee\Event\EmployeeStatusChanged;

final class Employee extends AggregateRoot
{
    private EmployeeId $id;
    private EmployeeName $name;
    private string $email;
    private string $department;
    private string $role;
    private EmployeeStatus $status;
    private \DateTimeImmutable $hiredAt;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    private function __construct(
        EmployeeId $id,
        EmployeeName $name,
        string $email,
        string $department,
        string $role
    ) {
        parent::__construct();

        $now = new \DateTimeImmutable();

        $this->id = $id;
        $this->name = $name;
        $this->email = mb_strtolower(trim($email));
        $this->department = trim($department);
        $this->role = trim($role);
        $this->status = EmployeeStatus::ACTIVE;

        $this->hiredAt = $now;
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    public static function hire(
        EmployeeName $name,
        string $email,
        string $department,
        string $role
    ): self {
        $employee = new self(
            EmployeeId::new(),
            $name,
            $email,
            $department,
            $role
        );

        $employee->recordDomainEvent(
            new EmployeeHired(
                $employee->id,
                $employee->name,
                $employee->email,
                $employee->department,
                $employee->role,
                $employee->hiredAt
            )
        );

        return $employee;
    }

    public static function rehydrate(
        EmployeeId $id,
        EmployeeName $name,
        string $email,
        string $department,
        string $role,
        EmployeeStatus $status,
        \DateTimeImmutable $hiredAt,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt
    ): self {
        $self = new self($id, $name, $email, $department, $role);
        $self->status = $status;
        $self->hiredAt = $hiredAt;
        $self->createdAt = $createdAt;
        $self->updatedAt = $updatedAt;
        return $self;
    }

    public function changeStatus(EmployeeStatus $newStatus): void
    {
        if ($this->status === $newStatus) {
            return;
        }

        $this->status = $newStatus;
        $this->updatedAt = new \DateTimeImmutable();

        $this->recordDomainEvent(
            new EmployeeStatusChanged(
                $this->id,
                $this->status
            )
        );
    }

    public function id(): EmployeeId { return $this->id; }
    public function name(): EmployeeName { return $this->name; }
    public function email(): string { return $this->email; }
    public function department(): string { return $this->department; }
    public function role(): string { return $this->role; }
    public function status(): EmployeeStatus { return $this->status; }
    public function hiredAt(): \DateTimeImmutable { return $this->hiredAt; }
    public function createdAt(): \DateTimeImmutable { return $this->createdAt; }
    public function updatedAt(): \DateTimeImmutable { return $this->updatedAt; }
}