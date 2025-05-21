<?php


declare(strict_types=1);


namespace DoctrineMigrations;


use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20250513092742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création sécurisée de la table admin avec index unique sur email';
    }


    public function up(Schema $schema): void
    {
        // Crée la table admin uniquement si elle n’existe pas déjà
        $this->addSql(<<<'SQL'
            DO $$
            BEGIN
                IF NOT EXISTS (
                    SELECT FROM pg_tables
                    WHERE tablename = 'admin'
                ) THEN
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


        // Crée l’index unique uniquement s’il n’existe pas
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX IF NOT EXISTS UNIQ_IDENTIFIER_EMAIL ON admin (email);
        SQL);
    }


    public function down(Schema $schema): void
    {
        // Supprime la table admin si elle existe
        $this->addSql(<<<'SQL'
            DROP TABLE IF EXISTS admin;
        SQL);
    }
}

