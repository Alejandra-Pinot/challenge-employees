<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'employees')]
class EmployeeRecord
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    private string $id;

    #[ORM\Column(length: 120)]
    private string $name;

    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    #[ORM\Column(length: 80)]
    private string $department;

    #[ORM\Column(length: 80)]
    private string $role;

    #[ORM\Column(length: 20)]
    private string $status;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $hiredAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updatedAt;


    public function getId(): string { return $this->id; }
    public function setId(string $id): void { $this->id = $id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function getDepartment(): string { return $this->department; }
    public function setDepartment(string $department): void { $this->department = $department; }

    public function getRole(): string { return $this->role; }
    public function setRole(string $role): void { $this->role = $role; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): void { $this->status = $status; }

    public function getHiredAt(): \DateTimeImmutable { return $this->hiredAt; }
    public function setHiredAt(\DateTimeImmutable $hiredAt): void { $this->hiredAt = $hiredAt; }

    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): void { $this->createdAt = $createdAt; }

    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void { $this->updatedAt = $updatedAt; }
}