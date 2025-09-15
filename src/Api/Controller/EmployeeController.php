<?php
declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\DTO\CreateEmployeeRequest;
use App\Api\Http\ProblemResponse;
use App\Application\Employee\Command\ChangeEmployeeStatusCommand;
use App\Application\Employee\Command\CreateEmployeeCommand;
use App\Application\Employee\Handler\ChangeEmployeeStatusHandler;
use App\Application\Employee\Handler\CreateEmployeeHandler;
use App\Application\Employee\Handler\GetEmployeeByIdHandler;
use App\Application\Employee\Handler\ListEmployeesHandler;
use App\Application\Employee\Query\GetEmployeeByIdQuery;
use App\Application\Employee\Query\ListEmployeesQuery;
use App\Domain\Employee\EmployeeStatus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class EmployeeController
{
    public function __construct(
        private CreateEmployeeHandler $createHandler,
        private ChangeEmployeeStatusHandler $statusHandler,
        private GetEmployeeByIdHandler $getHandler,
        private ListEmployeesHandler $listHandler,
        private ValidatorInterface $validator
    ) {}

    #[Route('/api/employees', name: 'create_employee', methods: ['POST'])]
    public function create(Request $req): JsonResponse
    {
        try {
            $data = json_decode($req->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable) {
            return ProblemResponse::badRequest('Invalid JSON body');
        }

        $dto = CreateEmployeeRequest::fromArray($data);
        $violations = $this->validator->validate($dto);
        if (\count($violations) > 0) {
            $normalized = [];
            foreach ($violations as $v) {
                $field = $v->getPropertyPath() ?: '_root';
                $normalized[$field][] = (string) $v->getMessage();
            }
            return ProblemResponse::validation($normalized); // 422
        }

        $cmd = new CreateEmployeeCommand($dto->name, $dto->email, $dto->department, $dto->role);
        try {
            $id = ($this->createHandler)($cmd);
            return new JsonResponse(['id' => $id, 'message' => 'Employee hired successfully'], 201);
        } catch (\DomainException $e) {
            return ProblemResponse::conflict($e->getMessage());
        } catch (\Throwable $e) {
            return ProblemResponse::badRequest($e->getMessage());
        }
    }

    #[Route('/api/employees', name: 'list_employees', methods: ['GET'])]
    public function list(Request $req): JsonResponse
    {
        $page  = (int) $req->query->get('page', 1);
        $limit = (int) $req->query->get('limit', 10);
        try {
            $data = ($this->listHandler)(new ListEmployeesQuery($page, $limit));
            return new JsonResponse(
                $data,
                200,
                [
                    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                    'Pragma'        => 'no-cache',
                    'Expires'       => '0',
                ]
            );
        } catch (\Throwable $e) {
            return ProblemResponse::badRequest($e->getMessage());
        }
    }

    #[Route('/api/employees/{id}', name: 'get_employee', methods: ['GET'])]
    public function getById(string $id): JsonResponse
    {
        try {
            $data = ($this->getHandler)(new GetEmployeeByIdQuery($id));
            return new JsonResponse($data, 200);
        } catch (\DomainException) {
            return ProblemResponse::notFound('Employee not found');
        } catch (\Throwable $e) {
            return ProblemResponse::badRequest($e->getMessage());
        }
    }

    #[Route('/api/employees/{id}/status', name: 'change_employee_status', methods: ['PATCH'])]
    public function changeStatus(string $id, Request $req): JsonResponse
    {
        try {
            $data = json_decode($req->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable) {
            return ProblemResponse::badRequest('Invalid JSON body');
        }

        try {
            $statusInput = (string)($data['status'] ?? '');
            $normalized = match (mb_strtolower(trim($statusInput))) {
                'active', 'activo'         => 'active',
                'inactive', 'inactivo'     => 'inactive',
                'vacation', 'vacaciones'   => 'vacation',
                'terminated', 'terminado', 'despedido' => 'terminated',
                default => throw new \InvalidArgumentException('Invalid status'),
            };
            $status = EmployeeStatus::from($normalized);
        } catch (\Throwable) {
            return ProblemResponse::badRequest('Invalid status');
        }

        try {
            ($this->statusHandler)(new ChangeEmployeeStatusCommand($id, $status));
            return new JsonResponse(['id' => $id, 'message' => 'Status updated']);
        } catch (\DomainException $e) {
            return $e->getMessage() === 'Employee not found'
                ? ProblemResponse::notFound($e->getMessage())
                : ProblemResponse::conflict($e->getMessage());
        } catch (\Throwable $e) {
            return ProblemResponse::badRequest($e->getMessage());
        }
    }
}