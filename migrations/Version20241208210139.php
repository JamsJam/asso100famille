<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241208210139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE one_time_event ADD is_free TINYINT(1) NOT NULL, ADD price INT DEFAULT NULL, ADD user_price INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recurring_event ADD is_free TINYINT(1) NOT NULL, ADD price INT DEFAULT NULL, ADD user_price INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recurring_event DROP is_free, DROP price, DROP user_price');
        $this->addSql('ALTER TABLE one_time_event DROP is_free, DROP price, DROP user_price');
    }
}
