<?php


declare(strict_types=1);


namespace DoctrineMigrations;


use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20250513083120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout de la table admin et (si nécessaire) de la contrainte animal → enclos';
    }


    public function up(Schema $schema): void
    {
        // Création de la table admin
        $this->addSql(<<<'SQL'
            CREATE TABLE admin (
                id SERIAL NOT NULL,
                email VARCHAR(180) NOT NULL,
                roles JSON NOT NULL,
                password VARCHAR(255) NOT NULL,
                PRIMARY KEY(id)
            )
        SQL);


        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON admin (email)
        SQL);


        // NE PAS RECRÉER enclos_id s’il existe déjà !


        // Création de la contrainte si elle n’existe pas déjà (à ajuster manuellement si besoin)
        $this->addSql(<<<'SQL'
            DO $$
            BEGIN
                IF NOT EXISTS (
                    SELECT 1 FROM pg_constraint WHERE conname = 'fk_6aab231fb1c0859'
                ) THEN
                    ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FB1C0859
                    FOREIGN KEY (enclos_id) REFERENCES enclos (id);
                END IF;
            END
            $$;
        SQL);


        // Création de l’index (même logique si nécessaire)
        $this->addSql(<<<'SQL'
            CREATE INDEX IF NOT EXISTS IDX_6AAB231FB1C0859 ON animal (enclos_id)
        SQL);
    }


    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE animal DROP CONSTRAINT IF EXISTS FK_6AAB231FB1C0859
        SQL);


        $this->addSql(<<<'SQL'
            DROP INDEX IF EXISTS IDX_6AAB231FB1C0859
        SQL);


        // ATTENTION : on ne supprime pas la colonne enclos_id ici car elle existait avant


        $this->addSql(<<<'SQL'
            DROP TABLE admin
        SQL);
    }
}





