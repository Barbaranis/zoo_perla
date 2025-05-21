<?php


declare(strict_types=1);


namespace DoctrineMigrations;


use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20250512123034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout de la clé étrangère enclos_id à animal (sans recréer la table enclos)';
    }


    public function up(Schema $schema): void
    {
        // Ne pas recréer la table enclos car elle existe déjà


        $this->addSql(<<<'SQL'
            ALTER TABLE animal ADD enclos_id INT NOT NULL
        SQL);


        $this->addSql(<<<'SQL'
            ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FB1C0859 FOREIGN KEY (enclos_id) REFERENCES enclos (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);


        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6AAB231FB1C0859 ON animal (enclos_id)
        SQL);
    }


    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE animal DROP CONSTRAINT FK_6AAB231FB1C0859
        SQL);


        $this->addSql(<<<'SQL'
            DROP INDEX IDX_6AAB231FB1C0859
        SQL);


        $this->addSql(<<<'SQL'
            ALTER TABLE animal DROP enclos_id
        SQL);
    }
}

