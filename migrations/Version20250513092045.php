<?php


declare(strict_types=1);


namespace DoctrineMigrations;


use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20250513092045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout de la table admin, de la table enclos, et de la contrainte entre animal et enclos, avec sécurité si les éléments existent déjà';
    }


    public function up(Schema $schema): void
    {
        // Crée la table admin seulement si elle n'existe pas
        $this->addSql(<<<'SQL'
            DO $$
            BEGIN
                IF NOT EXISTS (SELECT FROM pg_tables WHERE tablename = 'admin') THEN
                    CREATE TABLE admin (
                        id SERIAL NOT NULL,
                        email VARCHAR(180) NOT NULL,
                        roles JSON NOT NULL,
                        password VARCHAR(255) NOT NULL,
                        PRIMARY KEY(id)
                    );
                END IF;
            END
            $$;
        SQL);


        // Crée l'index unique sur email uniquement s'il n'existe pas
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX IF NOT EXISTS UNIQ_IDENTIFIER_EMAIL ON admin (email)
        SQL);


        // Crée la table enclos seulement si elle n'existe pas
        $this->addSql(<<<'SQL'
            DO $$
            BEGIN
                IF NOT EXISTS (SELECT FROM pg_tables WHERE tablename = 'enclos') THEN
                    CREATE TABLE enclos (
                        id SERIAL NOT NULL,
                        nom VARCHAR(255) NOT NULL,
                        type VARCHAR(255) NOT NULL,
                        capacite INT NOT NULL,
                        localisation VARCHAR(255),
                        PRIMARY KEY(id)
                    );
                END IF;
            END
            $$;
        SQL);


        // Ajoute la colonne enclos_id uniquement si elle n'existe pas
        $this->addSql(<<<'SQL'
            DO $$
            BEGIN
                IF NOT EXISTS (
                    SELECT 1
                    FROM information_schema.columns
                    WHERE table_name = 'animal' AND column_name = 'enclos_id'
                ) THEN
                    ALTER TABLE animal ADD enclos_id INT;
                END IF;
            END
            $$;
        SQL);


        // Ajoute la contrainte si elle n'existe pas
        $this->addSql(<<<'SQL'
            DO $$
            BEGIN
                IF NOT EXISTS (
                    SELECT 1 FROM pg_constraint WHERE conname = 'fk_6aab231fb1c0859'
                ) THEN
                    ALTER TABLE animal
                    ADD CONSTRAINT FK_6AAB231FB1C0859 FOREIGN KEY (enclos_id)
                    REFERENCES enclos (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
                END IF;
            END
            $$;
        SQL);


        // Ajoute l’index si inexistant
        $this->addSql(<<<'SQL'
            CREATE INDEX IF NOT EXISTS IDX_6AAB231FB1C0859 ON animal (enclos_id)
        SQL);
    }


    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE animal DROP CONSTRAINT IF EXISTS FK_6AAB231FB1C0859');
        $this->addSql('DROP INDEX IF EXISTS IDX_6AAB231FB1C0859');
        $this->addSql('ALTER TABLE animal DROP COLUMN IF EXISTS enclos_id');
        $this->addSql('DROP TABLE IF EXISTS admin');
        $this->addSql('DROP TABLE IF EXISTS enclos');
    }
}



