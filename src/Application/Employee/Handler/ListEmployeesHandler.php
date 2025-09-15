<?php

declare(strict_types=1);

namespace App\Application\Employee\Handler;

use App\Application\Employee\Query\ListEmployeesQuery;
use App\Domain\Employee\EmployeeRepository;

final class ListEmployeesHandler
{
    public function __construct(private EmployeeRepository $repo)
    {
    }

    /**
     * @return array{
     *   page:int, limit:int, total:int, totalPages:int,
     *   items: array<int, array{id:string,name:string,email:string,department:string,role:string,status:string,hiredAt:string,createdAt:string,updatedAt:string}>
     * }
     */
    public function __invoke(ListEmployeesQuery $q): array
    {
        $page  = max(1, $q->page);
        $limit = max(1, min(100, $q->limit));

        [$employees, $total] = $this->repo->paginate($page, $limit);

        $items = array_map(static function ($e) {
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
        }, $employees);

        return [
            'page'       => $page,
            'limit'      => $limit,
            'total'      => $total,
            'totalPages' => (int) ceil($total / $limit),
            'items'      => $items,
        ];
    }
}
