<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240624004607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trip_activity ADD activity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trip_activity ADD CONSTRAINT FK_4253A4A81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('CREATE INDEX IDX_4253A4A81C06096 ON trip_activity (activity_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trip_activity DROP FOREIGN KEY FK_4253A4A81C06096');
        $this->addSql('DROP INDEX IDX_4253A4A81C06096 ON trip_activity');
        $this->addSql('ALTER TABLE trip_activity DROP activity_id');
    }
}
