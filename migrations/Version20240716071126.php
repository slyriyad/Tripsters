<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716071126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expense CHANGE date date DATE NOT NULL');
        $this->addSql('ALTER TABLE trip CHANGE start_date start_date DATETIME NOT NULL, CHANGE end_date end_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE trip_activity CHANGE start_date start_date DATETIME NOT NULL, CHANGE end_date end_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user ADD image_size INT DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, CHANGE avatar image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_identifier_email TO UNIQ_8D93D649E7927C74');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trip_activity CHANGE start_date start_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE end_date end_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE user DROP image_size, DROP updated_at, CHANGE image_name avatar VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649e7927c74 TO UNIQ_IDENTIFIER_EMAIL');
        $this->addSql('ALTER TABLE expense CHANGE date date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE trip CHANGE start_date start_date DATE NOT NULL, CHANGE end_date end_date DATE NOT NULL');
    }
}
