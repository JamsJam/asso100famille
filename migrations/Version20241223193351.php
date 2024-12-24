<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241223193351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD ot_event_id INT DEFAULT NULL, ADD r_event_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD final_price INT NOT NULL, ADD is_paid TINYINT(1) NOT NULL, ADD type_event VARCHAR(10) NOT NULL, CHANGE is_user is_activ TINYINT(1) NOT NULL, CHANGE event_id email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955EE374FB5 FOREIGN KEY (ot_event_id) REFERENCES one_time_event (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A8088496 FOREIGN KEY (r_event_id) REFERENCES recurring_event (id)');
        $this->addSql('CREATE INDEX IDX_42C84955EE374FB5 ON reservation (ot_event_id)');
        $this->addSql('CREATE INDEX IDX_42C84955A8088496 ON reservation (r_event_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955EE374FB5');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A8088496');
        $this->addSql('DROP INDEX IDX_42C84955EE374FB5 ON reservation');
        $this->addSql('DROP INDEX IDX_42C84955A8088496 ON reservation');
        $this->addSql('ALTER TABLE reservation ADD is_user TINYINT(1) NOT NULL, DROP ot_event_id, DROP r_event_id, DROP created_at, DROP final_price, DROP is_activ, DROP is_paid, DROP type_event, CHANGE email event_id VARCHAR(255) NOT NULL');
    }
}
