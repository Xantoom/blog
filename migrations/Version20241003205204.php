<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241003205204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users ALTER firstname TYPE VARCHAR(127)');
        $this->addSql('ALTER TABLE users ALTER lastname TYPE VARCHAR(127)');
        $this->addSql('ALTER TABLE users ALTER email TYPE VARCHAR(127)');
        $this->addSql('ALTER TABLE users ALTER password TYPE VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users ALTER firstname TYPE VARCHAR(64)');
        $this->addSql('ALTER TABLE users ALTER lastname TYPE VARCHAR(64)');
        $this->addSql('ALTER TABLE users ALTER email TYPE VARCHAR(64)');
        $this->addSql('ALTER TABLE users ALTER password TYPE VARCHAR(48)');
    }
}
