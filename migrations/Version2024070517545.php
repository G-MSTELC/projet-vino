<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240705175453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajoute la colonne prix_total à la table cellier et modifie la colonne id dans la table commentaire';
    }

    public function up(Schema $schema): void
    {
        // Ajouter la colonne prix_total avec les bons paramètres
        $this->addSql('ALTER TABLE cellier ADD prix_total NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL');
        
        // Modifier la colonne id dans la table commentaire
        $this->addSql('ALTER TABLE commentaire MODIFY id INT AUTO_INCREMENT');
    }

    public function down(Schema $schema): void
    {
        // Supprimer la colonne prix_total
        $this->addSql('ALTER TABLE cellier DROP COLUMN prix_total');
        
        // Revertir la modification de la colonne id dans la table commentaire (à adapter si nécessaire)
        // Note: Cela nécessite de connaître l'état précédent de la colonne id avant la modification
        $this->addSql('ALTER TABLE commentaire MODIFY id INT AUTO_INCREMENT');
    }
}
