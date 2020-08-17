<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200815165231 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE apprenant_groupe (apprenant_id INT NOT NULL, groupe_id INT NOT NULL, INDEX IDX_1D224F8DC5697D6D (apprenant_id), INDEX IDX_1D224F8D7A45358C (groupe_id), PRIMARY KEY(apprenant_id, groupe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE apprenant_groupe ADD CONSTRAINT FK_1D224F8DC5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE apprenant_groupe ADD CONSTRAINT FK_1D224F8D7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE groupe_apprenant');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE groupe_apprenant (groupe_id INT NOT NULL, apprenant_id INT NOT NULL, INDEX IDX_947F95197A45358C (groupe_id), INDEX IDX_947F9519C5697D6D (apprenant_id), PRIMARY KEY(groupe_id, apprenant_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE groupe_apprenant ADD CONSTRAINT FK_947F95197A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_apprenant ADD CONSTRAINT FK_947F9519C5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE apprenant_groupe');
    }
}
