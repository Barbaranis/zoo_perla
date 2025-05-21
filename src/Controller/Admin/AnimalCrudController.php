<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class AnimalCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Animal::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom', 'Nom'),
            TextField::new('espece', 'Espèce'),
            IntegerField::new('age', 'Âge'),
            TextareaField::new('description', 'Description')->hideOnIndex(),
            ImageField::new('photo', 'Photo')
                ->setUploadDir('public/uploads/animals')
                ->setBasePath('/uploads/animals')
                ->setRequired(false),
            DateField::new('dateArrivee', 'Date d’arrivée'),
            AssociationField::new('enclos', 'Enclos'),
        ];
    }
}

