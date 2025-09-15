<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Employee\Employee;
use App\Domain\Employee\EmployeeName;
use App\Domain\Employee\EmployeeRepository;
use App\Domain\Employee\EmployeeStatus;
use App\Domain\Shared\ValueObject\EmployeeId;
use App\Infrastructure\Persistence\Doctrine\Entity\EmployeeRecord;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineEmployeeRepository implements EmployeeRepository
{
    public function __construct(private EntityManagerInterface $em) {}
    
    public function add(Employee $employee): void
    {
        $rec = $this->toRecord($employee, new EmployeeRecord());
        $this->em->persist($rec);
        $this->em->flush();
    }

    public function save(Employee $employee): void
    {
        $repo = $this->em->getRepository(EmployeeRecord::class);
        $rec = $repo->find($employee->id()->value()) ?? new EmployeeRecord();

        $this->toRecord($employee, $rec);
        $this->em->flush();
    }

    public function byId(string $id): ?Employee
    {
        $rec = $this->em->find(EmployeeRecord::class, $id);
        return $rec ? $this->toDomain($rec) : null;
    }

    public function byEmail(string $email): ?Employee
    {
        $rec = $this->em->getRepository(EmployeeRecord::class)
            ->findOneBy(['email' => mb_strtolower(trim($email))]);

        return $rec ? $this->toDomain($rec) : null;
    }

    /**
     * @return array{0: Employee[], 1: int} [items, total]
     */
    public function paginate(int $page, int $limit): array
    {
        $page  = max(1, $page);
        $limit = max(1, min(100, $limit));

        $repo = $this->em->getRepository(EmployeeRecord::class);

        // Items
        $qb = $this->em->createQueryBuilder()
            ->select('e')
            ->from(EmployeeRecord::class, 'e')
            ->orderBy('e.createdAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        /** @var EmployeeRecord[] $rows */
        $rows = $qb->getQuery()->getResult();

        $items = array_map(fn(EmployeeRecord $r) => $this->toDomain($r), $rows);
        $count = (int) $this->em->createQueryBuilder()
            ->select('COUNT(e.id)')
            ->from(EmployeeRecord::class, 'e')
            ->getQuery()
            ->getSingleScalarResult();

        return [$items, $count];
    }

    private function toRecord(Employee $e, EmployeeRecord $r): EmployeeRecord
    {
        $r->setId($e->id()->value());
        $r->setName($e->name()->value());
        $r->setEmail($e->email());
        $r->setDepartment($e->department());
        $r->setRole($e->role());
        $r->setStatus($e->status()->value);
        $r->setHiredAt($e->hiredAt());
        $r->setCreatedAt($e->createdAt());
        $r->setUpdatedAt($e->updatedAt());
        return $r;
    }

    private function toDomain(EmployeeRecord $r): Employee
    {
        return Employee::rehydrate(
            EmployeeId::fromString($r->getId()),
            EmployeeName::fromString($r->getName()),
            $r->getEmail(),
            $r->getDepartment(),
            $r->getRole(),
            EmployeeStatus::from($r->getStatus()),
            $r->getHiredAt(),
            $r->getCreatedAt(),
            $r->getUpdatedAt()
        );
    }
}