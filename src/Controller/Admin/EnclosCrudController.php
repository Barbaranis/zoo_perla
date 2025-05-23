<?php


namespace App\Controller\Admin;


use App\Entity\Enclos;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;


class EnclosCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Enclos::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom', 'Nom'),
            TextField::new('type', 'Type'),
            IntegerField::new('capacite', 'Capacité'),
        ];
    }
}


