<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
<<<<<<< HEAD
final class Version20200807223425 extends AbstractMigration
=======
<<<<<<< HEAD:migrations/Version20200808005259.php
final class Version20200808005259 extends AbstractMigration
=======
final class Version20200807223425 extends AbstractMigration
>>>>>>> d81094d1f824869b5485c04f20e3c428d06aada7:migrations/Version20200807223425.php
>>>>>>> d81094d1f824869b5485c04f20e3c428d06aada7
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
<<<<<<< HEAD
        $this->addSql('CREATE TABLE promo (id INT AUTO_INCREMENT NOT NULL, lieu VARCHAR(255) NOT NULL, reference_agate VARCHAR(255) NOT NULL, fabrique VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, titre VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, langue VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
=======
<<<<<<< HEAD:migrations/Version20200808005259.php
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2C3959A3A4D60759 ON groupe_competence (libelle)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E6D6B297A4D60759 ON profil (libelle)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4DC05AA0A4D60759 ON profil_sortie (libelle)');
=======
        $this->addSql('CREATE TABLE promo (id INT AUTO_INCREMENT NOT NULL, lieu VARCHAR(255) NOT NULL, reference_agate VARCHAR(255) NOT NULL, fabrique VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, titre VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, langue VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
>>>>>>> d81094d1f824869b5485c04f20e3c428d06aada7:migrations/Version20200807223425.php
>>>>>>> d81094d1f824869b5485c04f20e3c428d06aada7
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
<<<<<<< HEAD
        $this->addSql('DROP TABLE promo');
=======
<<<<<<< HEAD:migrations/Version20200808005259.php
        $this->addSql('DROP INDEX UNIQ_2C3959A3A4D60759 ON groupe_competence');
        $this->addSql('DROP INDEX UNIQ_E6D6B297A4D60759 ON profil');
        $this->addSql('DROP INDEX UNIQ_4DC05AA0A4D60759 ON profil_sortie');
=======
        $this->addSql('DROP TABLE promo');
>>>>>>> d81094d1f824869b5485c04f20e3c428d06aada7:migrations/Version20200807223425.php
>>>>>>> d81094d1f824869b5485c04f20e3c428d06aada7
    }
}
