<?php
declare(strict_types=1);

namespace App\Application\Employee\Query;

final class GetEmployeeByIdQuery
{
    public function __construct(public string $id) {}
}