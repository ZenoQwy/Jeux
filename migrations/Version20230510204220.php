<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230510204220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE plateforme_produit (plateforme_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_345A217391E226B (plateforme_id), INDEX IDX_345A217F347EFB (produit_id), PRIMARY KEY(plateforme_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE plateforme_produit ADD CONSTRAINT FK_345A217391E226B FOREIGN KEY (plateforme_id) REFERENCES plateforme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plateforme_produit ADD CONSTRAINT FK_345A217F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plateforme_produit DROP FOREIGN KEY FK_345A217391E226B');
        $this->addSql('ALTER TABLE plateforme_produit DROP FOREIGN KEY FK_345A217F347EFB');
        $this->addSql('DROP TABLE plateforme_produit');
    }
}
