<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241208204805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE one_time_event ADD start_hour DATETIME NOT NULL, ADD end_hour DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE recurring_event ADD start_hour DATETIME NOT NULL, ADD end_hour DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recurring_event DROP start_hour, DROP end_hour');
        $this->addSql('ALTER TABLE one_time_event DROP start_hour, DROP end_hour');
    }
}
