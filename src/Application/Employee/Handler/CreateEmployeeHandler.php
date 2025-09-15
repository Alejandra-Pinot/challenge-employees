<?php

declare(strict_types=1);

namespace App\Application\Employee\Handler;

use App\Application\Employee\Command\CreateEmployeeCommand;
use App\Domain\Employee\Employee;
use App\Domain\Employee\EmployeeName;
use App\Domain\Employee\EmployeeRepository;
use App\Domain\Shared\ValueObject\Email;

final class CreateEmployeeHandler
{
    public function __construct(private EmployeeRepository $repo)
    {
    }

    public function __invoke(CreateEmployeeCommand $cmd): string
    {
        if ($this->repo->byEmail(mb_strtolower(trim($cmd->email)))) {
            throw new \DomainException('Email already exists');
        }

        $employee = Employee::hire(
            EmployeeName::fromString($cmd->name),
            Email::fromString($cmd->email)->value(),
            $cmd->department,
            $cmd->role
        );

        $this->repo->add($employee);
        $this->repo->save($employee);

        return $employee->id()->value();
    }
}
