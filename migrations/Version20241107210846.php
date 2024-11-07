<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241107210846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_5eda3788f8697d13');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5EDA3788F8697D13 ON comment_approvals (comment_id)');
        $this->addSql('DROP INDEX idx_b95927c3f8697d13');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B95927C3F8697D13 ON comment_deletions (comment_id)');
        $this->addSql('DROP INDEX idx_4f779b5ef8697d13');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4F779B5EF8697D13 ON comment_edits (comment_id)');
        $this->addSql('ALTER TABLE comments ADD approval_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments ADD deletion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AFE65F000 FOREIGN KEY (approval_id) REFERENCES comment_approvals (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AF05F9129 FOREIGN KEY (deletion_id) REFERENCES comment_deletions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F9E962AFE65F000 ON comments (approval_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F9E962AF05F9129 ON comments (deletion_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_4F779B5EF8697D13');
        $this->addSql('CREATE INDEX idx_4f779b5ef8697d13 ON comment_edits (comment_id)');
        $this->addSql('ALTER TABLE comments DROP CONSTRAINT FK_5F9E962AFE65F000');
        $this->addSql('ALTER TABLE comments DROP CONSTRAINT FK_5F9E962AF05F9129');
        $this->addSql('DROP INDEX UNIQ_5F9E962AFE65F000');
        $this->addSql('DROP INDEX UNIQ_5F9E962AF05F9129');
        $this->addSql('ALTER TABLE comments DROP approval_id');
        $this->addSql('ALTER TABLE comments DROP deletion_id');
        $this->addSql('DROP INDEX UNIQ_5EDA3788F8697D13');
        $this->addSql('CREATE INDEX idx_5eda3788f8697d13 ON comment_approvals (comment_id)');
        $this->addSql('DROP INDEX UNIQ_B95927C3F8697D13');
        $this->addSql('CREATE INDEX idx_b95927c3f8697d13 ON comment_deletions (comment_id)');
    }
}
