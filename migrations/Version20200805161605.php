<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200805161605 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE profil_sortie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil_sortie_apprenant (profil_sortie_id INT NOT NULL, apprenant_id INT NOT NULL, INDEX IDX_982975846409EF73 (profil_sortie_id), INDEX IDX_98297584C5697D6D (apprenant_id), PRIMARY KEY(profil_sortie_id, apprenant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE profil_sortie_apprenant ADD CONSTRAINT FK_982975846409EF73 FOREIGN KEY (profil_sortie_id) REFERENCES profil_sortie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil_sortie_apprenant ADD CONSTRAINT FK_98297584C5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profil_sortie_apprenant DROP FOREIGN KEY FK_982975846409EF73');
        $this->addSql('DROP TABLE profil_sortie');
        $this->addSql('DROP TABLE profil_sortie_apprenant');
    }
}
