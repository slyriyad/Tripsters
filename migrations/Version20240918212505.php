<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240918212505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE forum_reply (id INT AUTO_INCREMENT NOT NULL, topic_id INT NOT NULL, author_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E5DC60371F55203D (topic_id), INDEX IDX_E5DC6037F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_topic (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_853478CCF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trip_invitation (id INT AUTO_INCREMENT NOT NULL, trip_id INT NOT NULL, invitee_id INT NOT NULL, creator_id INT NOT NULL, status VARCHAR(20) NOT NULL, sent_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_40FBAB4A5BC2E0E (trip_id), INDEX IDX_40FBAB47A512022 (invitee_id), INDEX IDX_40FBAB461220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE forum_reply ADD CONSTRAINT FK_E5DC60371F55203D FOREIGN KEY (topic_id) REFERENCES forum_topic (id)');
        $this->addSql('ALTER TABLE forum_reply ADD CONSTRAINT FK_E5DC6037F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_topic ADD CONSTRAINT FK_853478CCF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE trip_invitation ADD CONSTRAINT FK_40FBAB4A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id)');
        $this->addSql('ALTER TABLE trip_invitation ADD CONSTRAINT FK_40FBAB47A512022 FOREIGN KEY (invitee_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE trip_invitation ADD CONSTRAINT FK_40FBAB461220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE category_activity ADD icon VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE trip_user ADD role VARCHAR(25) NOT NULL');
        $this->addSql('ALTER TABLE user ADD name VARCHAR(25) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE forum_reply DROP FOREIGN KEY FK_E5DC60371F55203D');
        $this->addSql('ALTER TABLE forum_reply DROP FOREIGN KEY FK_E5DC6037F675F31B');
        $this->addSql('ALTER TABLE forum_topic DROP FOREIGN KEY FK_853478CCF675F31B');
        $this->addSql('ALTER TABLE trip_invitation DROP FOREIGN KEY FK_40FBAB4A5BC2E0E');
        $this->addSql('ALTER TABLE trip_invitation DROP FOREIGN KEY FK_40FBAB47A512022');
        $this->addSql('ALTER TABLE trip_invitation DROP FOREIGN KEY FK_40FBAB461220EA6');
        $this->addSql('DROP TABLE forum_reply');
        $this->addSql('DROP TABLE forum_topic');
        $this->addSql('DROP TABLE trip_invitation');
        $this->addSql('ALTER TABLE category_activity DROP icon');
        $this->addSql('ALTER TABLE user DROP name');
        $this->addSql('ALTER TABLE trip_user DROP role');
    }
}
