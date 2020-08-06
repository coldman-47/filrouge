<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
<<<<<<< HEAD:migrations/Version20200806140524.php
final class Version20200806140524 extends AbstractMigration
=======
final class Version20200806170931 extends AbstractMigration
>>>>>>> coldman:migrations/Version20200806170931.php
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
<<<<<<< HEAD:migrations/Version20200806140524.php
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E6D6B297A4D60759 ON profil (libelle)');
=======
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2C3959A3A4D60759 ON groupe_competence (libelle)');
>>>>>>> coldman:migrations/Version20200806170931.php
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
<<<<<<< HEAD:migrations/Version20200806140524.php
        $this->addSql('DROP INDEX UNIQ_E6D6B297A4D60759 ON profil');
=======
        $this->addSql('DROP INDEX UNIQ_2C3959A3A4D60759 ON groupe_competence');
>>>>>>> coldman:migrations/Version20200806170931.php
    }
}
