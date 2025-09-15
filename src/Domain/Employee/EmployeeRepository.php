<?php

declare(strict_types=1);

namespace App\Domain\Employee;

interface EmployeeRepository
{
    public function add(Employee $employee): void;
    public function save(Employee $employee): void;
    public function byId(string $id): ?Employee;
    public function byEmail(string $email): ?Employee;

    /**
     * @return array{0: Employee[], 1: int} [items, total]
    */
    public function paginate(int $page, int $limit): array;
}
