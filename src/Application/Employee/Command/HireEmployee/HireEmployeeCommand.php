<?php

declare(strict_types=1);

namespace App\Application\Employee\Command\HireEmployee;

final class HireEmployeeCommand
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $department,
        public readonly string $role
    ) {
    }
}
