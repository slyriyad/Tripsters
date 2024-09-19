<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240919012123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE forum_reply CHANGE author_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE forum_topic CHANGE author_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE trip ADD updated_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE forum_topic CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trip DROP updated_at');
        $this->addSql('ALTER TABLE forum_reply CHANGE author_id author_id INT DEFAULT NULL');
    }
}
