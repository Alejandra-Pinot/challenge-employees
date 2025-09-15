<?php

declare(strict_types=1);

namespace App\Application\Employee\Handler;

use App\Application\Employee\Query\GetEmployeeByIdQuery;
use App\Domain\Employee\EmployeeRepository;

final class GetEmployeeByIdHandler
{
    public function __construct(private EmployeeRepository $repo)
    {
    }

    /**
     * @return array<string,mixed>
     */
    public function __invoke(GetEmployeeByIdQuery $q): array
    {
        $e = $this->repo->byId($q->id);
        if (!$e) {
            throw new \DomainException('Employee not found');
        }

        return [
            'id'         => $e->id()->value(),
            'name'       => $e->name()->value(),
            'email'      => $e->email(),
            'department' => $e->department(),
            'role'       => $e->role(),
            'status'     => $e->status()->value,
            'hiredAt'    => $e->hiredAt()->format(DATE_ATOM),
            'createdAt'  => $e->createdAt()->format(DATE_ATOM),
            'updatedAt'  => $e->updatedAt()->format(DATE_ATOM),
        ];
    }
}
