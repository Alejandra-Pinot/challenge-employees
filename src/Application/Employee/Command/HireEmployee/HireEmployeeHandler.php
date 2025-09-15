<?php
declare(strict_types=1);

namespace App\Application\Employee\Command\HireEmployee;

use App\Domain\Employee\Employee;
use App\Domain\Employee\EmployeeName;
use App\Domain\Employee\EmployeeRepository;

final class HireEmployeeHandler
{
    public function __construct(private readonly EmployeeRepository $repo) {}
    
    public function __invoke(HireEmployeeCommand $cmd): string
    {
        $name = EmployeeName::fromString($cmd->name);

        $employee = Employee::hire(
            $name,
            $cmd->email,
            $cmd->department,
            $cmd->role
        );

        $this->repo->save($employee);

        return $employee->id()->value();
    }
}