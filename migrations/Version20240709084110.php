<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240709084110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trip_expense DROP FOREIGN KEY FK_1BD6C80BA5BC2E0E');
        $this->addSql('ALTER TABLE trip_expense DROP FOREIGN KEY FK_1BD6C80BF395DB7B');
        $this->addSql('DROP TABLE trip_expense');
        $this->addSql('ALTER TABLE expense ADD paid_by_id INT DEFAULT NULL, CHANGE date date DATE NOT NULL');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA67F9BC654 FOREIGN KEY (paid_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2D3A8DA67F9BC654 ON expense (paid_by_id)');
        $this->addSql('ALTER TABLE expense_split ADD CONSTRAINT FK_12FD3D2BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE trip CHANGE start_date start_date DATETIME NOT NULL, CHANGE end_date end_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE trip_activity CHANGE start_date start_date DATETIME NOT NULL, CHANGE end_date end_date DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trip_expense (id INT AUTO_INCREMENT NOT NULL, trip_id INT NOT NULL, expense_id INT NOT NULL, INDEX IDX_1BD6C80BA5BC2E0E (trip_id), INDEX IDX_1BD6C80BF395DB7B (expense_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE trip_expense ADD CONSTRAINT FK_1BD6C80BA5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE trip_expense ADD CONSTRAINT FK_1BD6C80BF395DB7B FOREIGN KEY (expense_id) REFERENCES expense (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE expense_split DROP FOREIGN KEY FK_12FD3D2BA76ED395');
        $this->addSql('ALTER TABLE trip_activity CHANGE start_date start_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE end_date end_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA67F9BC654');
        $this->addSql('DROP INDEX IDX_2D3A8DA67F9BC654 ON expense');
        $this->addSql('ALTER TABLE expense DROP paid_by_id, CHANGE date date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE trip CHANGE start_date start_date DATE NOT NULL, CHANGE end_date end_date DATE NOT NULL');
    }
}
