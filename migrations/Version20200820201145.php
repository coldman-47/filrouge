<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200820201145 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE apprenant_livrable_pariel');
        $this->addSql('DROP TABLE groupe_apprenant');
        $this->addSql('ALTER TABLE fil_discution ADD commentaire_id INT DEFAULT NULL, ADD apprenant_livravle_partiel_id INT NOT NULL');
        $this->addSql('ALTER TABLE fil_discution ADD CONSTRAINT FK_41858334BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id)');
        $this->addSql('ALTER TABLE fil_discution ADD CONSTRAINT FK_41858334A196CA78 FOREIGN KEY (apprenant_livravle_partiel_id) REFERENCES apprenant_livrable_partiel (id)');
        $this->addSql('CREATE INDEX IDX_41858334BA9CD190 ON fil_discution (commentaire_id)');
        $this->addSql('CREATE INDEX IDX_41858334A196CA78 ON fil_discution (apprenant_livravle_partiel_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE apprenant_livrable_pariel (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT NOT NULL, livrable_partiel_id INT NOT NULL, etat VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, delai_at DATETIME NOT NULL, INDEX IDX_D91BC293519178C4 (livrable_partiel_id), INDEX IDX_D91BC293C5697D6D (apprenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE groupe_apprenant (groupe_id INT NOT NULL, apprenant_id INT NOT NULL, INDEX IDX_947F95197A45358C (groupe_id), INDEX IDX_947F9519C5697D6D (apprenant_id), PRIMARY KEY(groupe_id, apprenant_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE apprenant_livrable_pariel ADD CONSTRAINT FK_D91BC293519178C4 FOREIGN KEY (livrable_partiel_id) REFERENCES livrable_partiel (id)');
        $this->addSql('ALTER TABLE apprenant_livrable_pariel ADD CONSTRAINT FK_D91BC293C5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE groupe_apprenant ADD CONSTRAINT FK_947F95197A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_apprenant ADD CONSTRAINT FK_947F9519C5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fil_discution DROP FOREIGN KEY FK_41858334BA9CD190');
        $this->addSql('ALTER TABLE fil_discution DROP FOREIGN KEY FK_41858334A196CA78');
        $this->addSql('DROP INDEX IDX_41858334BA9CD190 ON fil_discution');
        $this->addSql('DROP INDEX IDX_41858334A196CA78 ON fil_discution');
        $this->addSql('ALTER TABLE fil_discution DROP commentaire_id, DROP apprenant_livravle_partiel_id');
    }
}
