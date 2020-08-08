<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200808005259 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2C3959A3A4D60759 ON groupe_competence (libelle)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E6D6B297A4D60759 ON profil (libelle)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4DC05AA0A4D60759 ON profil_sortie (libelle)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_2C3959A3A4D60759 ON groupe_competence');
        $this->addSql('DROP INDEX UNIQ_E6D6B297A4D60759 ON profil');
        $this->addSql('DROP INDEX UNIQ_4DC05AA0A4D60759 ON profil_sortie');
    }
}
