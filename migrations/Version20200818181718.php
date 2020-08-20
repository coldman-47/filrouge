<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200818181718 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brief_ma_promo (brief_id INT NOT NULL, promo_id INT NOT NULL, INDEX IDX_6E0C4800757FABFF (brief_id), INDEX IDX_6E0C4800D0C07AFF (promo_id), PRIMARY KEY(brief_id, promo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE brief_ma_promo ADD CONSTRAINT FK_6E0C4800757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id)');
        $this->addSql('ALTER TABLE brief_ma_promo ADD CONSTRAINT FK_6E0C4800D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('DROP TABLE groupe_apprenant');
        $this->addSql('ALTER TABLE brief CHANGE description description VARCHAR(255) NOT NULL, CHANGE contexte contexte VARCHAR(255) NOT NULL, CHANGE livrable_attendu livrable_attendu VARCHAR(255) NOT NULL, CHANGE modalite_pedagogique modalite_pedagogique VARCHAR(255) NOT NULL, CHANGE critere_evaluation critere_evaluation VARCHAR(255) NOT NULL, CHANGE image_promo image_promo LONGBLOB NOT NULL, CHANGE archiver archiver TINYINT(1) NOT NULL, CHANGE create_at create_at DATETIME NOT NULL, CHANGE etat etat VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE groupe_apprenant (groupe_id INT NOT NULL, apprenant_id INT NOT NULL, INDEX IDX_947F95197A45358C (groupe_id), INDEX IDX_947F9519C5697D6D (apprenant_id), PRIMARY KEY(groupe_id, apprenant_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE groupe_apprenant ADD CONSTRAINT FK_947F95197A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_apprenant ADD CONSTRAINT FK_947F9519C5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE brief_ma_promo');
        $this->addSql('ALTER TABLE brief CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE contexte contexte VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE livrable_attendu livrable_attendu VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE modalite_pedagogique modalite_pedagogique VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE critere_evaluation critere_evaluation VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image_promo image_promo LONGBLOB DEFAULT NULL, CHANGE archiver archiver TINYINT(1) DEFAULT NULL, CHANGE create_at create_at DATETIME DEFAULT NULL, CHANGE etat etat VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
