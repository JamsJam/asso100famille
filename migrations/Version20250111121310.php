<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250111121310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonement (id INT AUTO_INCREMENT NOT NULL, adherent_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expired_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_A4B598D25F06C53 (adherent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adherent (id INT NOT NULL, is_verified TINYINT(1) NOT NULL, ddn DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', profession VARCHAR(255) NOT NULL, adresse VARCHAR(100) NOT NULL, adresse2 VARCHAR(255) DEFAULT NULL, codepostal VARCHAR(5) NOT NULL, ville VARCHAR(255) NOT NULL, telephone VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `admin` (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE famille (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, adherent_id INT NOT NULL, membre INT NOT NULL, nb_adultes INT NOT NULL, nb_mineurs INT NOT NULL, nb_hommes INT NOT NULL, nb_femmes INT NOT NULL, nb_garcons INT NOT NULL, nb_filles INT NOT NULL, INDEX IDX_2473F213C54C8C93 (type_id), UNIQUE INDEX UNIQ_2473F21325F06C53 (adherent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE famille_type (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE one_time_event (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, start_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', start_hour DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_hour DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_free TINYINT(1) NOT NULL, price INT DEFAULT NULL, user_price INT DEFAULT NULL, image VARCHAR(255) NOT NULL, crop JSON DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recurring_event (id INT AUTO_INCREMENT NOT NULL, recurring_rule_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, start_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', start_hour DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_hour DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_free TINYINT(1) NOT NULL, price INT DEFAULT NULL, user_price INT DEFAULT NULL, image VARCHAR(255) NOT NULL, crop JSON DEFAULT NULL, UNIQUE INDEX UNIQ_51B1C7F8ACB896D8 (recurring_rule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recurring_rule (id INT AUTO_INCREMENT NOT NULL, days_of_week VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, ot_event_id INT DEFAULT NULL, r_event_id INT DEFAULT NULL, adherent_id INT DEFAULT NULL, quantity INT NOT NULL, prix VARCHAR(5) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', final_price INT NOT NULL, is_activ TINYINT(1) NOT NULL, is_paid TINYINT(1) NOT NULL, type_event VARCHAR(10) NOT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_42C84955EE374FB5 (ot_event_id), INDEX IDX_42C84955A8088496 (r_event_id), INDEX IDX_42C8495525F06C53 (adherent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abonement ADD CONSTRAINT FK_A4B598D25F06C53 FOREIGN KEY (adherent_id) REFERENCES adherent (id)');
        $this->addSql('ALTER TABLE adherent ADD CONSTRAINT FK_90D3F060BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `admin` ADD CONSTRAINT FK_880E0D76BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE famille ADD CONSTRAINT FK_2473F213C54C8C93 FOREIGN KEY (type_id) REFERENCES famille_type (id)');
        $this->addSql('ALTER TABLE famille ADD CONSTRAINT FK_2473F21325F06C53 FOREIGN KEY (adherent_id) REFERENCES adherent (id)');
        $this->addSql('ALTER TABLE recurring_event ADD CONSTRAINT FK_51B1C7F8ACB896D8 FOREIGN KEY (recurring_rule_id) REFERENCES recurring_rule (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955EE374FB5 FOREIGN KEY (ot_event_id) REFERENCES one_time_event (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A8088496 FOREIGN KEY (r_event_id) REFERENCES recurring_event (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495525F06C53 FOREIGN KEY (adherent_id) REFERENCES adherent (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonement DROP FOREIGN KEY FK_A4B598D25F06C53');
        $this->addSql('ALTER TABLE adherent DROP FOREIGN KEY FK_90D3F060BF396750');
        $this->addSql('ALTER TABLE `admin` DROP FOREIGN KEY FK_880E0D76BF396750');
        $this->addSql('ALTER TABLE famille DROP FOREIGN KEY FK_2473F213C54C8C93');
        $this->addSql('ALTER TABLE famille DROP FOREIGN KEY FK_2473F21325F06C53');
        $this->addSql('ALTER TABLE recurring_event DROP FOREIGN KEY FK_51B1C7F8ACB896D8');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955EE374FB5');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A8088496');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495525F06C53');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE abonement');
        $this->addSql('DROP TABLE adherent');
        $this->addSql('DROP TABLE `admin`');
        $this->addSql('DROP TABLE famille');
        $this->addSql('DROP TABLE famille_type');
        $this->addSql('DROP TABLE one_time_event');
        $this->addSql('DROP TABLE recurring_event');
        $this->addSql('DROP TABLE recurring_rule');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
