<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250915023217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employees CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE name name VARCHAR(120) NOT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE department department VARCHAR(80) NOT NULL, CHANGE role role VARCHAR(80) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employees CHANGE id id VARCHAR(36) NOT NULL, CHANGE name name VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE department department VARCHAR(100) NOT NULL, CHANGE role role VARCHAR(100) NOT NULL');
    }
}
