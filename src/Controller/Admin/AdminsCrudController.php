<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminsCrudController extends AbstractCrudController



{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher) {}

    public static function getEntityFqcn(): string

    { 
        return Admin::class;
    }

    public function configureFields(string $pageName): iterable
    
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('email'),
            ArrayField::new('roles'),
            TextField::new('password')->onlyOnForms()->setHelp('Mot de passe non
             visible, sera hashé automatiquement'),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void

    {
        if ($entityInstance instanceof Admin && $entityInstance->getPassword()) {
            $entityInstance->setPassword(
                $this->passwordHasher->hashPassword($entityInstance, $entityInstance->getPassword())
            );
        }

        parent::persistEntity($entityManager, $entityInstance);
    }


    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void

    {
        if ($entityInstance instanceof Admin && $entityInstance->getPassword()) {
            $entityInstance->setPassword(
                $this->passwordHasher->hashPassword($entityInstance, $entityInstance->getPassword())
            );
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

}

