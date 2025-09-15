<?php

declare(strict_types=1);

namespace App\Application\Employee\Query;

final class ListEmployeesQuery
{
    public function __construct(public int $page = 1, public int $limit = 10)
    {
    }
}
