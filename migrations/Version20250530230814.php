<?php


declare(strict_types=1);


namespace DoctrineMigrations;


use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20250530230814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout des champs password et roles à Employe, et contrainte NOT NULL sur email';
    }


    public function up(Schema $schema): void
    {
        // Ajout des colonnes sans NOT NULL d’abord
        $this->addSql('ALTER TABLE employe ADD password VARCHAR(255)');
        $this->addSql('ALTER TABLE employe ADD roles JSON DEFAULT \'[]\'');


        // Mise à jour des données existantes avec un mot de passe temporaire
        $this->addSql("UPDATE employe SET email = CONCAT('employe_', id, '@arcadia.local') WHERE email IS NULL");
        $this->addSql("UPDATE employe SET password = 'temp123' WHERE password IS NULL");


        // Appliquer les contraintes NOT NULL après remplissage
        $this->addSql('ALTER TABLE employe ALTER password SET NOT NULL');
        $this->addSql('ALTER TABLE employe ALTER roles SET NOT NULL');
        $this->addSql('ALTER TABLE employe ALTER email SET NOT NULL');


        // Ajout de l’index unique sur email
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F804D3B9E7927C74 ON employe (email)');
    }


    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_F804D3B9E7927C74');
        $this->addSql('ALTER TABLE employe DROP password');
        $this->addSql('ALTER TABLE employe DROP roles');
        $this->addSql('ALTER TABLE employe ALTER email DROP NOT NULL');
    }

    
}




