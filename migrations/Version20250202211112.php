<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250202211112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'populate famillyType entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO famille_type (nom) VALUES ('traditionnelle'), ('nucleaire'), ('pacse')");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM famille_type WHERE nom IN ('traditionnelle', 'nucleaire', 'pacse')");

    }
}
