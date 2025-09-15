<?php

declare(strict_types=1);

namespace App\Api\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class CreateEmployeeRequest
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 120)]
    public string $name = '';

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 180)]
    public string $email = '';

    #[Assert\NotBlank]
    #[Assert\Length(max: 80)]
    public string $department = '';

    #[Assert\NotBlank]
    #[Assert\Length(max: 80)]
    public string $role = '';

    /** @param array<string,mixed> $data */
    public static function fromArray(array $data): self
    {
        $self = new self();
        $self->name = (string)($data['name'] ?? '');
        $self->email = (string)($data['email'] ?? '');
        $self->department = (string)($data['department'] ?? '');
        $self->role = (string)($data['role'] ?? '');
        return $self;
    }
}
