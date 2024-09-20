<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240917211709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE comment_approvals_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE comment_deletions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE comment_edits_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE comments_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE post_categories_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE post_edits_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE post_publishes_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE posts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE comment_approvals (id INT NOT NULL, approvedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, approved BOOLEAN NOT NULL, approvedBy_id INT NOT NULL, comment_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5EDA3788FACFC38A ON comment_approvals (approvedBy_id)');
        $this->addSql('CREATE INDEX IDX_5EDA3788F8697D13 ON comment_approvals (comment_id)');
        $this->addSql('CREATE TABLE comment_deletions (id INT NOT NULL, deletedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deletedBy_id INT NOT NULL, comment_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B95927C363D8C20E ON comment_deletions (deletedBy_id)');
        $this->addSql('CREATE INDEX IDX_B95927C3F8697D13 ON comment_deletions (comment_id)');
        $this->addSql('CREATE TABLE comment_edits (id INT NOT NULL, content TEXT NOT NULL, editedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, editedBy_id INT NOT NULL, comment_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4F779B5EAD3D511A ON comment_edits (editedBy_id)');
        $this->addSql('CREATE INDEX IDX_4F779B5EF8697D13 ON comment_edits (comment_id)');
        $this->addSql('CREATE TABLE comments (id INT NOT NULL, content TEXT NOT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, createdBy_id INT NOT NULL, post_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5F9E962A3174800F ON comments (createdBy_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A4B89032C ON comments (post_id)');
        $this->addSql('CREATE TABLE post_categories (id INT NOT NULL, name VARCHAR(64) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE post_edits (id INT NOT NULL, title VARCHAR(255) DEFAULT NULL, content TEXT DEFAULT NULL, preview TEXT DEFAULT NULL, banner TEXT DEFAULT NULL, editedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, editedBy_id INT NOT NULL, category_id INT DEFAULT NULL, post_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_71F68F40AD3D511A ON post_edits (editedBy_id)');
        $this->addSql('CREATE INDEX IDX_71F68F4012469DE2 ON post_edits (category_id)');
        $this->addSql('CREATE INDEX IDX_71F68F404B89032C ON post_edits (post_id)');
        $this->addSql('CREATE TABLE post_publishes (id INT NOT NULL, published BOOLEAN NOT NULL, publishedAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, publishedBy_id INT NOT NULL, post_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4A819B272954ECA ON post_publishes (publishedBy_id)');
        $this->addSql('CREATE INDEX IDX_4A819B274B89032C ON post_publishes (post_id)');
        $this->addSql('CREATE TABLE posts (id INT NOT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, preview TEXT NOT NULL, banner TEXT NOT NULL, views INT NOT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, createdBy_id INT NOT NULL, category_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_885DBAFA3174800F ON posts (createdBy_id)');
        $this->addSql('CREATE INDEX IDX_885DBAFA12469DE2 ON posts (category_id)');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, firstname VARCHAR(64) NOT NULL, lastname VARCHAR(64) NOT NULL, email VARCHAR(64) NOT NULL, password VARCHAR(48) NOT NULL, roles JSON NOT NULL, active BOOLEAN NOT NULL, profilePicture VARCHAR(255) NOT NULL, createdAt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('ALTER TABLE comment_approvals ADD CONSTRAINT FK_5EDA3788FACFC38A FOREIGN KEY (approvedBy_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment_approvals ADD CONSTRAINT FK_5EDA3788F8697D13 FOREIGN KEY (comment_id) REFERENCES comments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment_deletions ADD CONSTRAINT FK_B95927C363D8C20E FOREIGN KEY (deletedBy_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment_deletions ADD CONSTRAINT FK_B95927C3F8697D13 FOREIGN KEY (comment_id) REFERENCES comments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment_edits ADD CONSTRAINT FK_4F779B5EAD3D511A FOREIGN KEY (editedBy_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment_edits ADD CONSTRAINT FK_4F779B5EF8697D13 FOREIGN KEY (comment_id) REFERENCES comments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A3174800F FOREIGN KEY (createdBy_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A4B89032C FOREIGN KEY (post_id) REFERENCES posts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post_edits ADD CONSTRAINT FK_71F68F40AD3D511A FOREIGN KEY (editedBy_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post_edits ADD CONSTRAINT FK_71F68F4012469DE2 FOREIGN KEY (category_id) REFERENCES post_categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post_edits ADD CONSTRAINT FK_71F68F404B89032C FOREIGN KEY (post_id) REFERENCES posts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post_publishes ADD CONSTRAINT FK_4A819B272954ECA FOREIGN KEY (publishedBy_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post_publishes ADD CONSTRAINT FK_4A819B274B89032C FOREIGN KEY (post_id) REFERENCES posts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA3174800F FOREIGN KEY (createdBy_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA12469DE2 FOREIGN KEY (category_id) REFERENCES post_categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE comment_approvals_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE comment_deletions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE comment_edits_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE comments_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE post_categories_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE post_edits_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE post_publishes_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE posts_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('ALTER TABLE comment_approvals DROP CONSTRAINT FK_5EDA3788FACFC38A');
        $this->addSql('ALTER TABLE comment_approvals DROP CONSTRAINT FK_5EDA3788F8697D13');
        $this->addSql('ALTER TABLE comment_deletions DROP CONSTRAINT FK_B95927C363D8C20E');
        $this->addSql('ALTER TABLE comment_deletions DROP CONSTRAINT FK_B95927C3F8697D13');
        $this->addSql('ALTER TABLE comment_edits DROP CONSTRAINT FK_4F779B5EAD3D511A');
        $this->addSql('ALTER TABLE comment_edits DROP CONSTRAINT FK_4F779B5EF8697D13');
        $this->addSql('ALTER TABLE comments DROP CONSTRAINT FK_5F9E962A3174800F');
        $this->addSql('ALTER TABLE comments DROP CONSTRAINT FK_5F9E962A4B89032C');
        $this->addSql('ALTER TABLE post_edits DROP CONSTRAINT FK_71F68F40AD3D511A');
        $this->addSql('ALTER TABLE post_edits DROP CONSTRAINT FK_71F68F4012469DE2');
        $this->addSql('ALTER TABLE post_edits DROP CONSTRAINT FK_71F68F404B89032C');
        $this->addSql('ALTER TABLE post_publishes DROP CONSTRAINT FK_4A819B272954ECA');
        $this->addSql('ALTER TABLE post_publishes DROP CONSTRAINT FK_4A819B274B89032C');
        $this->addSql('ALTER TABLE posts DROP CONSTRAINT FK_885DBAFA3174800F');
        $this->addSql('ALTER TABLE posts DROP CONSTRAINT FK_885DBAFA12469DE2');
        $this->addSql('DROP TABLE comment_approvals');
        $this->addSql('DROP TABLE comment_deletions');
        $this->addSql('DROP TABLE comment_edits');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE post_categories');
        $this->addSql('DROP TABLE post_edits');
        $this->addSql('DROP TABLE post_publishes');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE users');
    }
}
