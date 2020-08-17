<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200809154712 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe_apprenant DROP FOREIGN KEY FK_947F95197A45358C');
        $this->addSql('DROP TABLE formateur');
        $this->addSql('DROP TABLE formateur_user');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE groupe_apprenant');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE formateur (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE formateur_user (formateur_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3A592726155D8F51 (formateur_id), INDEX IDX_3A592726A76ED395 (user_id), PRIMARY KEY(formateur_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL, libelle INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE groupe_apprenant (groupe_id INT NOT NULL, apprenant_id INT NOT NULL, INDEX IDX_947F95197A45358C (groupe_id), INDEX IDX_947F9519C5697D6D (apprenant_id), PRIMARY KEY(groupe_id, apprenant_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE formateur_user ADD CONSTRAINT FK_3A592726155D8F51 FOREIGN KEY (formateur_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formateur_user ADD CONSTRAINT FK_3A592726A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_apprenant ADD CONSTRAINT FK_947F95197A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_apprenant ADD CONSTRAINT FK_947F9519C5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id) ON DELETE CASCADE');
    }
}
