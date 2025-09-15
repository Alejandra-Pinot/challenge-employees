<?php

declare(strict_types=1);

namespace App\Application\Employee\Command;

final class CreateEmployeeCommand
{
    public function __construct(
        public string $name,
        public string $email,
        public string $department,
        public string $role
    ) {
    }
}
