<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240408105919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories_post (categories_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_82B9340BA21214B7 (categories_id), INDEX IDX_82B9340B4B89032C (post_id), PRIMARY KEY(categories_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categories_post ADD CONSTRAINT FK_82B9340BA21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_post ADD CONSTRAINT FK_82B9340B4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_categories DROP FOREIGN KEY FK_198B4FA94B89032C');
        $this->addSql('ALTER TABLE post_categories DROP FOREIGN KEY FK_198B4FA9A21214B7');
        $this->addSql('DROP TABLE post_categories');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post_categories (post_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_198B4FA9A21214B7 (categories_id), INDEX IDX_198B4FA94B89032C (post_id), PRIMARY KEY(post_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE post_categories ADD CONSTRAINT FK_198B4FA94B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_categories ADD CONSTRAINT FK_198B4FA9A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_post DROP FOREIGN KEY FK_82B9340BA21214B7');
        $this->addSql('ALTER TABLE categories_post DROP FOREIGN KEY FK_82B9340B4B89032C');
        $this->addSql('DROP TABLE categories_post');
    }
}
