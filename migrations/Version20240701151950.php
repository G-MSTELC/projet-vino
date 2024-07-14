<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240701151950 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // Create user table first since it's referenced by other tables
        $this->addSql('CREATE TABLE user (
            id BIGINT AUTO_INCREMENT NOT NULL,
            username VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            roles JSON NOT NULL,
            created_at DATETIME DEFAULT NULL,
            updated_at DATETIME DEFAULT NULL,
            reset_token VARCHAR(255) DEFAULT NULL,
            UNIQUE INDEX UNIQ_8D93D649F85E0677 (username),
            UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create cellier table
        $this->addSql('CREATE TABLE cellier (
            id BIGINT AUTO_INCREMENT NOT NULL,
            user_id BIGINT DEFAULT NULL,
            created_at DATETIME DEFAULT NULL,
            updated_at DATETIME DEFAULT NULL,
            nom VARCHAR(255) NOT NULL,
            INDEX IDX_B3BEBA45A76ED395 (user_id),
            PRIMARY KEY(id),
            CONSTRAINT FK_B3BEBA45A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create bouteille table
        $this->addSql('CREATE TABLE bouteille (
            id BIGINT AUTO_INCREMENT NOT NULL,
            user_id BIGINT DEFAULT NULL,
            nom VARCHAR(255) NOT NULL,
            prix NUMERIC(7, 2) NOT NULL,
            pays VARCHAR(255) NOT NULL,
            format VARCHAR(255) NOT NULL,
            lien_produit VARCHAR(255) NOT NULL,
            src_image VARCHAR(512) NOT NULL,
            srcset_image VARCHAR(512) NOT NULL,
            designation VARCHAR(255) NOT NULL,
            degre VARCHAR(255) DEFAULT NULL,
            taux_sucre VARCHAR(255) DEFAULT NULL,
            couleur VARCHAR(255) DEFAULT NULL,
            producteur VARCHAR(255) DEFAULT NULL,
            agent_promotion VARCHAR(255) DEFAULT NULL,
            type VARCHAR(255) DEFAULT NULL,
            millesime VARCHAR(255) DEFAULT NULL,
            cepage VARCHAR(255) DEFAULT NULL,
            region VARCHAR(255) DEFAULT NULL,
            produit_quebec VARCHAR(255) DEFAULT NULL,
            pastille_gout_titre VARCHAR(255) DEFAULT NULL,
            pastille_image_src VARCHAR(512) DEFAULT NULL,
            created_at DATETIME DEFAULT NULL,
            updated_at DATETIME DEFAULT NULL,
            INDEX IDX_11157C4CA76ED395 (user_id),
            PRIMARY KEY(id),
            CONSTRAINT FK_11157C4CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create bouteille_cellier table
        $this->addSql('CREATE TABLE bouteille_cellier (
            id BIGINT AUTO_INCREMENT NOT NULL,
            bouteille_id BIGINT DEFAULT NULL,
            cellier_id BIGINT DEFAULT NULL,
            user_id BIGINT DEFAULT NULL,
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            quantite INT NOT NULL,
            INDEX IDX_9C971BDFF1966394 (bouteille_id),
            INDEX IDX_9C971BDF22E35211 (cellier_id),
            INDEX IDX_9C971BDFA76ED395 (user_id),
            PRIMARY KEY(id),
            CONSTRAINT FK_9C971BDFF1966394 FOREIGN KEY (bouteille_id) REFERENCES bouteille (id) ON DELETE CASCADE,
            CONSTRAINT FK_9C971BDF22E35211 FOREIGN KEY (cellier_id) REFERENCES cellier (id) ON DELETE CASCADE,
            CONSTRAINT FK_9C971BDFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create liste table
        $this->addSql('CREATE TABLE liste (
            id BIGINT AUTO_INCREMENT NOT NULL,
            user_id BIGINT NOT NULL,
            nom VARCHAR(255) NOT NULL,
            created_at DATETIME DEFAULT NULL,
            updated_at DATETIME DEFAULT NULL,
            INDEX IDX_FCF22AF4A76ED395 (user_id),
            PRIMARY KEY(id),
            CONSTRAINT FK_FCF22AF4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create bouteille_liste table
        $this->addSql('CREATE TABLE bouteille_liste (
            id BIGINT AUTO_INCREMENT NOT NULL,
            bouteille_id BIGINT DEFAULT NULL,
            liste_id BIGINT DEFAULT NULL,
            created_at DATETIME DEFAULT NULL,
            updated_at DATETIME DEFAULT NULL,
            INDEX IDX_BOUTEILLE_LISTE_BOUTEILLE_ID (bouteille_id),
            INDEX IDX_BOUTEILLE_LISTE_LISTE_ID (liste_id),
            PRIMARY KEY(id),
            CONSTRAINT FK_BOUTEILLE_LISTE_BOUTEILLE_ID FOREIGN KEY (bouteille_id) REFERENCES bouteille (id) ON DELETE CASCADE,
            CONSTRAINT FK_BOUTEILLE_LISTE_LISTE_ID FOREIGN KEY (liste_id) REFERENCES liste (id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create bouteille_personnalisee table
        $this->addSql('CREATE TABLE bouteille_personnalisee (
            id BIGINT AUTO_INCREMENT NOT NULL,
            user_id BIGINT NOT NULL,
            created_at DATETIME DEFAULT NULL,
            updated_at DATETIME DEFAULT NULL,
            nom VARCHAR(255) NOT NULL,
            pays VARCHAR(255) NOT NULL,
            region VARCHAR(255) NOT NULL,
            couleur VARCHAR(255) NOT NULL,
            format VARCHAR(255) NOT NULL,
            prix NUMERIC(8, 2) NOT NULL,
            producteur VARCHAR(255) NOT NULL,
            millesime SMALLINT NOT NULL,
            cepage VARCHAR(255) NOT NULL,
            taux_sucre VARCHAR(255) NOT NULL,
            degre VARCHAR(255) NOT NULL,
            type VARCHAR(255) NOT NULL,
            INDEX IDX_96437F9FA76ED395 (user_id),
            PRIMARY KEY(id),
            CONSTRAINT FK_96437F9FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create commentaire table
        $this->addSql('CREATE TABLE commentaire (
            id BIGINT AUTO_INCREMENT NOT NULL,
            bouteille_id BIGINT DEFAULT NULL,
            user_id BIGINT DEFAULT NULL,
            created_at DATETIME DEFAULT NULL,
            updated_at DATETIME DEFAULT NULL,
            INDEX IDX_67F068BCA76ED395 (user_id),
            PRIMARY KEY(id),
            CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE,
            CONSTRAINT FK_67F068BCBF396750 FOREIGN KEY (bouteille_id) REFERENCES bouteille (id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Optionally add more constraints as needed
    }

    public function down(Schema $schema): void
    {
        // Drop all tables
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE bouteille_personnalisee');
        $this->addSql('DROP TABLE bouteille_liste');
        $this->addSql('DROP TABLE liste');
        $this->addSql('DROP TABLE bouteille_cellier');
        $this->addSql('DROP TABLE bouteille');
        $this->addSql('DROP TABLE cellier');
        $this->addSql('DROP TABLE user');
    }
}
