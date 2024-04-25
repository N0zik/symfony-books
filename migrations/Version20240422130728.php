<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422130728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salles_travail ADD wifi TINYINT(1) NOT NULL, ADD projecteur TINYINT(1) NOT NULL, ADD tableau TINYINT(1) NOT NULL, ADD prise_electrique TINYINT(1) NOT NULL, ADD television TINYINT(1) NOT NULL, ADD climatisation TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE salles_travail DROP wifi, DROP projecteur, DROP tableau, DROP prise_electrique, DROP television, DROP climatisation');
    }
}
