<?php
declare(strict_types=1);

namespace App\Tests\Domain\Shared\ValueObject;

use App\Domain\Shared\ValueObject\Email;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    public function test_valid_email(): void
    {
        $email = Email::fromString('Mayra@Example.com');
        $this->assertSame('Mayra@Example.com', (string) $email); // solo verifica que se guarda igual
    }

    public function test_invalid_email_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Email::fromString('no-es-email');
    }
}