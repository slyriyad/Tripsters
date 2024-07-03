<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240703125136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trip CHANGE start_date start_date DATETIME NOT NULL, CHANGE end_date end_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE trip_activity CHANGE start_date start_date DATETIME NOT NULL, CHANGE end_date end_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE trip_expense ADD date DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trip CHANGE start_date start_date DATE NOT NULL, CHANGE end_date end_date DATE NOT NULL');
        $this->addSql('ALTER TABLE trip_expense DROP date');
        $this->addSql('ALTER TABLE trip_activity CHANGE start_date start_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE end_date end_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
