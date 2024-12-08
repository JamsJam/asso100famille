<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241029060342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE famille (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, user_id INT NOT NULL, membre INT NOT NULL, nb_adultes INT NOT NULL, nb_mineurs INT NOT NULL, nb_homme INT NOT NULL, nb_femmes INT NOT NULL, nb_garcons INT NOT NULL, nb_filles INT NOT NULL, INDEX IDX_2473F213C54C8C93 (type_id), UNIQUE INDEX UNIQ_2473F213A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE famille_type (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE famille ADD CONSTRAINT FK_2473F213C54C8C93 FOREIGN KEY (type_id) REFERENCES famille_type (id)');
        $this->addSql('ALTER TABLE famille ADD CONSTRAINT FK_2473F213A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD nom VARCHAR(255) NOT NULL, ADD prenom VARCHAR(255) NOT NULL, ADD ddn DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', ADD profession VARCHAR(255) NOT NULL, ADD adresse VARCHAR(100) NOT NULL, ADD adresse2 VARCHAR(255) DEFAULT NULL, ADD codepostal VARCHAR(5) NOT NULL, ADD ville VARCHAR(255) NOT NULL, ADD telephone VARCHAR(20) NOT NULL');
        $this->addSql("INSERT INTO famille_type (nom) VALUES ('Traditionnelle'),('Nucléaire'),('Pacsée')");
        
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM famille_type WHERE nom IN ('Traditionnelle', 'Nucléaire', 'Pacsée')");
        $this->addSql('ALTER TABLE famille DROP FOREIGN KEY FK_2473F213C54C8C93');
        $this->addSql('ALTER TABLE famille DROP FOREIGN KEY FK_2473F213A76ED395');
        $this->addSql('DROP TABLE famille');
        $this->addSql('DROP TABLE famille_type');
        $this->addSql('ALTER TABLE user DROP nom, DROP prenom, DROP ddn, DROP profession, DROP adresse, DROP adresse2, DROP codepostal, DROP ville, DROP telephone');
    }
}
