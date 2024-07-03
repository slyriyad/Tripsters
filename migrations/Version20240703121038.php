<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240703121038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A365B22FD FOREIGN KEY (category_activity_id) REFERENCES category_activity (id)');
        $this->addSql('ALTER TABLE trip_activity ADD start_date DATETIME NOT NULL, ADD end_date DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trip_activity DROP start_date, DROP end_date');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A365B22FD');
    }
}
