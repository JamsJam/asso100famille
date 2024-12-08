<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241208205024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE one_time_event DROP FOREIGN KEY FK_390625A1ACB896D8');
        $this->addSql('DROP INDEX UNIQ_390625A1ACB896D8 ON one_time_event');
        $this->addSql('ALTER TABLE one_time_event DROP recurring_rule_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE one_time_event ADD recurring_rule_id INT NOT NULL');
        $this->addSql('ALTER TABLE one_time_event ADD CONSTRAINT FK_390625A1ACB896D8 FOREIGN KEY (recurring_rule_id) REFERENCES recurring_rule (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_390625A1ACB896D8 ON one_time_event (recurring_rule_id)');
    }
}
