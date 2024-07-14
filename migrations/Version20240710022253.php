<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240710022253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Ajout de la colonne cellier_id à la table bouteille
        $this->addSql('ALTER TABLE bouteille ADD cellier_id BIGINT UNSIGNED DEFAULT NULL');

        // Ajout de la contrainte de clé étrangère
        $this->addSql('ALTER TABLE bouteille ADD CONSTRAINT FK_BOUTEILLE_CELLIER FOREIGN KEY (cellier_id) REFERENCES cellier (id)');
    }

    public function down(Schema $schema): void
    {
        // Suppression de la contrainte de clé étrangère
        $this->addSql('ALTER TABLE bouteille DROP FOREIGN KEY FK_BOUTEILLE_CELLIER');

        // Suppression de la colonne cellier_id
        $this->addSql('ALTER TABLE bouteille DROP COLUMN cellier_id');
    }
}
