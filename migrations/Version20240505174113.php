<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240505174113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, type_abonnement_id INT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, INDEX IDX_351268BBFB88E14F (utilisateur_id), INDEX IDX_351268BB813AF326 (type_abonnement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auteurs (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auteurs_livres (auteurs_id INT NOT NULL, livres_id INT NOT NULL, INDEX IDX_7BB9D45BAE784107 (auteurs_id), INDEX IDX_7BB9D45BEBF07F38 (livres_id), PRIMARY KEY(auteurs_id, livres_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE calendar (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, start DATETIME NOT NULL, description VARCHAR(255) NOT NULL, all_day TINYINT(1) NOT NULL, background_color VARCHAR(7) NOT NULL, text_color VARCHAR(7) NOT NULL, end DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaires (id INT AUTO_INCREMENT NOT NULL, utilisateurs_id INT NOT NULL, livres_id INT NOT NULL, commentaire VARCHAR(255) NOT NULL, date_ajout DATETIME NOT NULL, INDEX IDX_D9BEC0C41E969C5 (utilisateurs_id), INDEX IDX_D9BEC0C4EBF07F38 (livres_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaires_emprunts (id INT AUTO_INCREMENT NOT NULL, emprunts_id INT NOT NULL, utilisateurs_id INT NOT NULL, commentaire VARCHAR(255) NOT NULL, date_ajout DATETIME NOT NULL, INDEX IDX_F7B9E10410BD9597 (emprunts_id), INDEX IDX_F7B9E1041E969C5 (utilisateurs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emprunts (id INT AUTO_INCREMENT NOT NULL, utilisateurs_id INT NOT NULL, livres_id INT NOT NULL, date_demande DATE NOT NULL, date_restitution_previsionnelle DATE NOT NULL, date_restitution_effective DATE DEFAULT NULL, restitue TINYINT(1) NOT NULL, extension_emprunt TINYINT(1) NOT NULL, INDEX IDX_38FC80D1E969C5 (utilisateurs_id), INDEX IDX_38FC80DEBF07F38 (livres_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipements (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, wifi_equipement TINYINT(1) NOT NULL, projecteur_equipement TINYINT(1) NOT NULL, tableau_equipement TINYINT(1) NOT NULL, prise_electrique_equipement TINYINT(1) NOT NULL, television_equipement TINYINT(1) NOT NULL, climatisation_equipement TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipements_salles_travail (equipements_id INT NOT NULL, salles_travail_id INT NOT NULL, INDEX IDX_4D36259A852CCFF5 (equipements_id), INDEX IDX_4D36259A6E318F2B (salles_travail_id), PRIMARY KEY(equipements_id, salles_travail_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etats_livres (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livres (id INT AUTO_INCREMENT NOT NULL, etats_livres_id INT NOT NULL, image_name VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', nom VARCHAR(255) NOT NULL, annee_publication INT NOT NULL, resume LONGTEXT NOT NULL, disponibilite TINYINT(1) NOT NULL, INDEX IDX_927187A483A6FF20 (etats_livres_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notes (id INT AUTO_INCREMENT NOT NULL, utilisateurs_id INT NOT NULL, livres_id INT NOT NULL, note INT NOT NULL, INDEX IDX_11BA68C1E969C5 (utilisateurs_id), INDEX IDX_11BA68CEBF07F38 (livres_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservations (id INT AUTO_INCREMENT NOT NULL, utilisateurs_id INT NOT NULL, salles_travail_id INT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, INDEX IDX_4DA2391E969C5 (utilisateurs_id), INDEX IDX_4DA2396E318F2B (salles_travail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salles_travail (id INT AUTO_INCREMENT NOT NULL, image_name VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, capacite INT NOT NULL, description VARCHAR(255) NOT NULL, disponibilite TINYINT(1) NOT NULL, wifi TINYINT(1) NOT NULL, projecteur TINYINT(1) NOT NULL, tableau TINYINT(1) NOT NULL, prise_electrique TINYINT(1) NOT NULL, television TINYINT(1) NOT NULL, climatisation TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_abonnement (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, tarif NUMERIC(5, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateurs (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, adresse VARCHAR(255) NOT NULL, code_postal VARCHAR(50) NOT NULL, ville VARCHAR(100) NOT NULL, numero_telephone BIGINT NOT NULL, langue_du_site_fr TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_497B315EE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BBFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE abonnement ADD CONSTRAINT FK_351268BB813AF326 FOREIGN KEY (type_abonnement_id) REFERENCES type_abonnement (id)');
        $this->addSql('ALTER TABLE auteurs_livres ADD CONSTRAINT FK_7BB9D45BAE784107 FOREIGN KEY (auteurs_id) REFERENCES auteurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE auteurs_livres ADD CONSTRAINT FK_7BB9D45BEBF07F38 FOREIGN KEY (livres_id) REFERENCES livres (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C41E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4EBF07F38 FOREIGN KEY (livres_id) REFERENCES livres (id)');
        $this->addSql('ALTER TABLE commentaires_emprunts ADD CONSTRAINT FK_F7B9E10410BD9597 FOREIGN KEY (emprunts_id) REFERENCES emprunts (id)');
        $this->addSql('ALTER TABLE commentaires_emprunts ADD CONSTRAINT FK_F7B9E1041E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE emprunts ADD CONSTRAINT FK_38FC80D1E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE emprunts ADD CONSTRAINT FK_38FC80DEBF07F38 FOREIGN KEY (livres_id) REFERENCES livres (id)');
        $this->addSql('ALTER TABLE equipements_salles_travail ADD CONSTRAINT FK_4D36259A852CCFF5 FOREIGN KEY (equipements_id) REFERENCES equipements (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipements_salles_travail ADD CONSTRAINT FK_4D36259A6E318F2B FOREIGN KEY (salles_travail_id) REFERENCES salles_travail (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livres ADD CONSTRAINT FK_927187A483A6FF20 FOREIGN KEY (etats_livres_id) REFERENCES etats_livres (id)');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68C1E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68CEBF07F38 FOREIGN KEY (livres_id) REFERENCES livres (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA2391E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE reservations ADD CONSTRAINT FK_4DA2396E318F2B FOREIGN KEY (salles_travail_id) REFERENCES salles_travail (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BBFB88E14F');
        $this->addSql('ALTER TABLE abonnement DROP FOREIGN KEY FK_351268BB813AF326');
        $this->addSql('ALTER TABLE auteurs_livres DROP FOREIGN KEY FK_7BB9D45BAE784107');
        $this->addSql('ALTER TABLE auteurs_livres DROP FOREIGN KEY FK_7BB9D45BEBF07F38');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C41E969C5');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4EBF07F38');
        $this->addSql('ALTER TABLE commentaires_emprunts DROP FOREIGN KEY FK_F7B9E10410BD9597');
        $this->addSql('ALTER TABLE commentaires_emprunts DROP FOREIGN KEY FK_F7B9E1041E969C5');
        $this->addSql('ALTER TABLE emprunts DROP FOREIGN KEY FK_38FC80D1E969C5');
        $this->addSql('ALTER TABLE emprunts DROP FOREIGN KEY FK_38FC80DEBF07F38');
        $this->addSql('ALTER TABLE equipements_salles_travail DROP FOREIGN KEY FK_4D36259A852CCFF5');
        $this->addSql('ALTER TABLE equipements_salles_travail DROP FOREIGN KEY FK_4D36259A6E318F2B');
        $this->addSql('ALTER TABLE livres DROP FOREIGN KEY FK_927187A483A6FF20');
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68C1E969C5');
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68CEBF07F38');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA2391E969C5');
        $this->addSql('ALTER TABLE reservations DROP FOREIGN KEY FK_4DA2396E318F2B');
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE auteurs');
        $this->addSql('DROP TABLE auteurs_livres');
        $this->addSql('DROP TABLE calendar');
        $this->addSql('DROP TABLE commentaires');
        $this->addSql('DROP TABLE commentaires_emprunts');
        $this->addSql('DROP TABLE emprunts');
        $this->addSql('DROP TABLE equipements');
        $this->addSql('DROP TABLE equipements_salles_travail');
        $this->addSql('DROP TABLE etats_livres');
        $this->addSql('DROP TABLE livres');
        $this->addSql('DROP TABLE notes');
        $this->addSql('DROP TABLE reservations');
        $this->addSql('DROP TABLE salles_travail');
        $this->addSql('DROP TABLE type_abonnement');
        $this->addSql('DROP TABLE utilisateurs');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
