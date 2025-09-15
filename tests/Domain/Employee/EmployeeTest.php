<?php

declare(strict_types=1);

namespace App\Tests\Domain\Employee; // 👈 CAMBIO

use App\Domain\Employee\Employee;
use App\Domain\Employee\EmployeeName;
use App\Domain\Employee\EmployeeStatus;
use App\Domain\Employee\Event\EmployeeStatusChanged;
use PHPUnit\Framework\TestCase;

final class EmployeeTest extends TestCase
{
    public function test_hire_defaults_active_and_emits_hired(): void
    {
        $e = Employee::hire(
            EmployeeName::fromString('Mayra Pinot'),
            'mayra@example.com',
            'IT',
            'Developer'
        );

        $this->assertSame('active', $e->status()->value);
        $events = $e->getDomainEvents();
        $this->assertCount(1, $events);
    }

    public function test_change_status_emits_event(): void
    {
        $e = Employee::hire(
            EmployeeName::fromString('Mayra Pinot'),
            'mayra2@example.com',
            'IT',
            'Developer'
        );

        $e->pullDomainEvents(); // limpiar eventos de hire
        $e->changeStatus(EmployeeStatus::VACATION);

        $this->assertSame('vacation', $e->status()->value);
        $events = $e->getDomainEvents()->toArray();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(EmployeeStatusChanged::class, $events[0]);
    }
}
