<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200812131939 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE groupe_formateur');
        $this->addSql('DROP TABLE groupe_promo');
        $this->addSql('DROP TABLE promo_referentiel');
        $this->addSql('ALTER TABLE groupe ADD promo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C21D0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id)');
        $this->addSql('CREATE INDEX IDX_4B98C21D0C07AFF ON groupe (promo_id)');
        $this->addSql('ALTER TABLE promo DROP FOREIGN KEY FK_B0139AFB7A45358C');
        $this->addSql('DROP INDEX IDX_B0139AFB7A45358C ON promo');
        $this->addSql('ALTER TABLE promo DROP groupe_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE groupe_formateur (groupe_id INT NOT NULL, formateur_id INT NOT NULL, INDEX IDX_BDE2AD78155D8F51 (formateur_id), INDEX IDX_BDE2AD787A45358C (groupe_id), PRIMARY KEY(groupe_id, formateur_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE groupe_promo (groupe_id INT NOT NULL, promo_id INT NOT NULL, INDEX IDX_E5BE54ABD0C07AFF (promo_id), INDEX IDX_E5BE54AB7A45358C (groupe_id), PRIMARY KEY(groupe_id, promo_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE promo_referentiel (promo_id INT NOT NULL, referentiel_id INT NOT NULL, INDEX IDX_638B8B6B805DB139 (referentiel_id), INDEX IDX_638B8B6BD0C07AFF (promo_id), PRIMARY KEY(promo_id, referentiel_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE groupe_formateur ADD CONSTRAINT FK_BDE2AD78155D8F51 FOREIGN KEY (formateur_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_formateur ADD CONSTRAINT FK_BDE2AD787A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_promo ADD CONSTRAINT FK_E5BE54AB7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_promo ADD CONSTRAINT FK_E5BE54ABD0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promo_referentiel ADD CONSTRAINT FK_638B8B6B805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promo_referentiel ADD CONSTRAINT FK_638B8B6BD0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C21D0C07AFF');
        $this->addSql('DROP INDEX IDX_4B98C21D0C07AFF ON groupe');
        $this->addSql('ALTER TABLE groupe DROP promo_id');
        $this->addSql('ALTER TABLE promo ADD groupe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE promo ADD CONSTRAINT FK_B0139AFB7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('CREATE INDEX IDX_B0139AFB7A45358C ON promo (groupe_id)');
    }
}
