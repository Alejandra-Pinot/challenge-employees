<?php
declare(strict_types=1);

namespace App\Application\Employee\Handler;

use App\Application\Employee\Command\ChangeEmployeeStatusCommand;
use App\Domain\Employee\EmployeeRepository;

final class ChangeEmployeeStatusHandler
{
    public function __construct(private EmployeeRepository $repo) {}

    public function __invoke(ChangeEmployeeStatusCommand $cmd): void
    {
        $employee = $this->repo->byId($cmd->employeeId);
        if (!$employee) {
            throw new \DomainException('Employee not found');
        }

        $employee->changeStatus($cmd->newStatus);
        $this->repo->save($employee);
    }
}