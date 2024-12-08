<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241208203848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE one_time_event (id INT AUTO_INCREMENT NOT NULL, recurring_rule_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, start_date DATETIME NOT NULL, end_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_390625A1ACB896D8 (recurring_rule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recurring_event (id INT AUTO_INCREMENT NOT NULL, recurring_rule_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, start_date DATETIME NOT NULL, end_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_51B1C7F8ACB896D8 (recurring_rule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recurring_rule (id INT AUTO_INCREMENT NOT NULL, frequency VARCHAR(50) NOT NULL, finterval INT NOT NULL, days_of_week JSON NOT NULL, until VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE one_time_event ADD CONSTRAINT FK_390625A1ACB896D8 FOREIGN KEY (recurring_rule_id) REFERENCES recurring_rule (id)');
        $this->addSql('ALTER TABLE recurring_event ADD CONSTRAINT FK_51B1C7F8ACB896D8 FOREIGN KEY (recurring_rule_id) REFERENCES recurring_rule (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE one_time_event DROP FOREIGN KEY FK_390625A1ACB896D8');
        $this->addSql('ALTER TABLE recurring_event DROP FOREIGN KEY FK_51B1C7F8ACB896D8');
        $this->addSql('DROP TABLE one_time_event');
        $this->addSql('DROP TABLE recurring_event');
        $this->addSql('DROP TABLE recurring_rule');
    }
}
