<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240706164428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modification de la structure des tables bouteille, cellier, bouteille_cellier et bouteille_liste';
    }

    public function up(Schema $schema): void
    {
        // Vérifier si la colonne description existe avant de la supprimer
        if ($schema->getTable('bouteille')->hasColumn('description')) {
            $this->addSql('ALTER TABLE bouteille DROP description');
        }

        // Ajouter la colonne prix_total dans la table cellier si elle n'existe pas déjà
        if (!$schema->getTable('cellier')->hasColumn('prix_total')) {
            $this->addSql('ALTER TABLE cellier ADD prix_total NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL');
        }

        // Supprimer les colonnes created_at et updated_at de la table bouteille_cellier si elles existent
        if ($schema->getTable('bouteille_cellier')->hasColumn('created_at')) {
            $this->addSql('ALTER TABLE bouteille_cellier DROP created_at');
        }
        if ($schema->getTable('bouteille_cellier')->hasColumn('updated_at')) {
            $this->addSql('ALTER TABLE bouteille_cellier DROP updated_at');
        }

        // Supprimer les clés étrangères pour bouteille_liste si elles existent
        $this->addSql('ALTER TABLE bouteille_liste DROP FOREIGN KEY IF EXISTS FK_BOUTEILLE_LISTE_BOUTEILLE_ID');
        $this->addSql('ALTER TABLE bouteille_liste DROP FOREIGN KEY IF EXISTS FK_BOUTEILLE_LISTE_LISTE_ID');

        // Renommer les index pour bouteille_liste s'ils existent
        $this->addSql('ALTER TABLE bouteille_liste DROP INDEX IF EXISTS IDX_BOUTEILLE_LISTE_BOUTEILLE_ID');
        $this->addSql('ALTER TABLE bouteille_liste DROP INDEX IF EXISTS IDX_BOUTEILLE_LISTE_LISTE_ID');
        $this->addSql('ALTER TABLE bouteille_liste ADD INDEX IDX_BOUTEILLE_LISTE_BOUTEILLE_ID (bouteille_id)');
        $this->addSql('ALTER TABLE bouteille_liste ADD INDEX IDX_BOUTEILLE_LISTE_LISTE_ID (liste_id)');

        // Supprimer et rétablir les clés étrangères pour bouteille_cellier
        $this->addSql('ALTER TABLE bouteille_cellier DROP FOREIGN KEY IF EXISTS FK_9C971BDFA76ED395');
        $this->addSql('ALTER TABLE bouteille_cellier DROP FOREIGN KEY IF EXISTS FK_9C971BDFF1966394');
        $this->addSql('ALTER TABLE bouteille_cellier DROP FOREIGN KEY IF EXISTS FK_9C971BDF22E35211');
        $this->addSql('ALTER TABLE bouteille_cellier ADD CONSTRAINT FK_9C971BDFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bouteille_cellier ADD CONSTRAINT FK_9C971BDFF1966394 FOREIGN KEY (bouteille_id) REFERENCES bouteille (id)');
        $this->addSql('ALTER TABLE bouteille_cellier ADD CONSTRAINT FK_9C971BDF22E35211 FOREIGN KEY (cellier_id) REFERENCES cellier (id)');

        // Créer l'index sur user_id pour bouteille_cellier si elle n'existe pas déjà
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_9C971BDFA76ED395 ON bouteille_cellier (user_id)');

        // Modifier la colonne id dans la table commentaire si nécessaire
        $this->addSql('ALTER TABLE commentaire MODIFY id INT AUTO_INCREMENT');
    }

    public function down(Schema $schema): void
    {
        // Rétablir la colonne description dans la table bouteille si elle n'existe pas déjà
        if (!$schema->getTable('bouteille')->hasColumn('description')) {
            $this->addSql('ALTER TABLE bouteille ADD description LONGTEXT NOT NULL');
        }

        // Supprimer la colonne prix_total de la table cellier si elle existe
        if ($schema->getTable('cellier')->hasColumn('prix_total')) {
            $this->addSql('ALTER TABLE cellier DROP prix_total');
        }

        // Ajouter les colonnes created_at et updated_at dans la table bouteille_cellier si elles n'existent pas déjà
        if (!$schema->getTable('bouteille_cellier')->hasColumn('created_at')) {
            $this->addSql('ALTER TABLE bouteille_cellier ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        }
        if (!$schema->getTable('bouteille_cellier')->hasColumn('updated_at')) {
            $this->addSql('ALTER TABLE bouteille_cellier ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        }

        // Rétablir les clés étrangères pour bouteille_liste
        $this->addSql('ALTER TABLE bouteille_liste DROP FOREIGN KEY IF EXISTS FK_BOUTEILLE_LISTE_BOUTEILLE_ID');
        $this->addSql('ALTER TABLE bouteille_liste DROP FOREIGN KEY IF EXISTS FK_BOUTEILLE_LISTE_LISTE_ID');
        $this->addSql('ALTER TABLE bouteille_liste ADD CONSTRAINT FK_BOUTEILLE_LISTE_BOUTEILLE_ID FOREIGN KEY (bouteille_id) REFERENCES bouteille (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bouteille_liste ADD CONSTRAINT FK_BOUTEILLE_LISTE_LISTE_ID FOREIGN KEY (liste_id) REFERENCES liste (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bouteille_liste DROP INDEX IF EXISTS IDX_BOUTEILLE_LISTE_BOUTEILLE_ID');
        $this->addSql('ALTER TABLE bouteille_liste DROP INDEX IF EXISTS IDX_BOUTEILLE_LISTE_LISTE_ID');
    }
}
