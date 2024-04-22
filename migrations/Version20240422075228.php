<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422075228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipements ADD wifi_equipement TINYINT(1) NOT NULL, ADD projecteur_equipement TINYINT(1) NOT NULL, ADD tableau_equipement TINYINT(1) NOT NULL, ADD prise_electrique_equipement TINYINT(1) NOT NULL, ADD television_equipement TINYINT(1) NOT NULL, ADD climatisation_equipement TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipements DROP wifi_equipement, DROP projecteur_equipement, DROP tableau_equipement, DROP prise_electrique_equipement, DROP television_equipement, DROP climatisation_equipement');
    }
}
