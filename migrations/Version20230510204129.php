<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230510204129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_plateforme DROP FOREIGN KEY FK_953A351C391E226B');
        $this->addSql('ALTER TABLE produit_plateforme DROP FOREIGN KEY FK_953A351CF347EFB');
        $this->addSql('DROP TABLE produit_plateforme');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produit_plateforme (produit_id INT NOT NULL, plateforme_id INT NOT NULL, INDEX IDX_953A351CF347EFB (produit_id), INDEX IDX_953A351C391E226B (plateforme_id), PRIMARY KEY(produit_id, plateforme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE produit_plateforme ADD CONSTRAINT FK_953A351C391E226B FOREIGN KEY (plateforme_id) REFERENCES plateforme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_plateforme ADD CONSTRAINT FK_953A351CF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
    }
}
