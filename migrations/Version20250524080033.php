<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250524080033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE admin (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON admin (email)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE animal (id SERIAL NOT NULL, enclos_id INT NOT NULL, nom VARCHAR(255) NOT NULL, espece VARCHAR(255) NOT NULL, age INT NOT NULL, description TEXT DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, date_arrivee TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6AAB231FB1C0859 ON animal (enclos_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE employe (id SERIAL NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, poste VARCHAR(100) NOT NULL, email VARCHAR(180) DEFAULT NULL, telephone VARCHAR(20) DEFAULT NULL, date_embauche DATE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE enclos (id SERIAL NOT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, capacite INT NOT NULL, localisation VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE visite (id SERIAL NOT NULL, nom VARCHAR(255) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FB1C0859 FOREIGN KEY (enclos_id) REFERENCES enclos (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE animal DROP CONSTRAINT FK_6AAB231FB1C0859
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE admin
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE animal
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE employe
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE enclos
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE visite
        SQL);
    }
}
