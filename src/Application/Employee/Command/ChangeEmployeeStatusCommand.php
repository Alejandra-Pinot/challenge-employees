<?php

declare(strict_types=1);

namespace App\Application\Employee\Command;

use App\Domain\Employee\EmployeeStatus;

final class ChangeEmployeeStatusCommand
{
    public function __construct(
        public string $employeeId,
        public EmployeeStatus $newStatus
    ) {
    }
}
