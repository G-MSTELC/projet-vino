<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240707011357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modify commentaire table: add content field and update foreign key constraints';
    }

    public function up(Schema $schema): void
    {
        // Supprimer toutes les contraintes de clé étrangère existantes sur la table `commentaire`
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY IF EXISTS FK_67F068BC76ED395');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY IF EXISTS FK_67F068BC1E7650A');

        // Vérifier si la colonne `content` existe, sinon l'ajouter
        if (!$schema->getTable('commentaire')->hasColumn('content')) {
            $this->addSql('ALTER TABLE commentaire ADD content LONGTEXT NOT NULL');
        }

        // Vérifier si la colonne `prix_total` existe, sinon l'ajouter
        if (!$schema->getTable('commentaire')->hasColumn('prix_total')) {
            $this->addSql('ALTER TABLE commentaire ADD prix_total DECIMAL(10, 2) NOT NULL');
        }

        // Modifier la colonne `id` uniquement si nécessaire
        $this->addSql('ALTER TABLE commentaire MODIFY id INT AUTO_INCREMENT');

        // Recréer les contraintes de clé étrangère avec les nouvelles colonnes
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC1E7650A FOREIGN KEY (bouteille_id) REFERENCES bouteille (id)');
    }

    public function down(Schema $schema): void
    {
        // Supprimer toutes les contraintes de clé étrangère créées lors du rollback
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY IF EXISTS FK_67F068BC76ED395');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY IF EXISTS FK_67F068BC1E7650A');

        // Supprimer la colonne `content` si nécessaire lors du rollback
        if ($schema->getTable('commentaire')->hasColumn('content')) {
            $this->addSql('ALTER TABLE commentaire DROP COLUMN content');
        }

        // Supprimer la colonne `prix_total` si nécessaire lors du rollback
        if ($schema->getTable('commentaire')->hasColumn('prix_total')) {
            $this->addSql('ALTER TABLE commentaire DROP COLUMN prix_total');
        }

        // Réinitialiser la colonne `id` à son état original si nécessaire
        $this->addSql('ALTER TABLE commentaire MODIFY id INT');
    }
}
