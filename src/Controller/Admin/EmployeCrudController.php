<?php

namespace App\Controller\Admin;

use App\Entity\Employe;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EmployeCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public static function getEntityFqcn(): string
    {
        return Employe::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('prenom'),
            TextField::new('poste'),
            EmailField::new('email'),
            TextField::new('telephone')->hideOnIndex(),
            DateField::new('dateEmbauche'),
            TextField::new('password')
                ->setFormType(PasswordType::class)
                ->onlyOnForms()
                ->setLabel('Mot de passe')
        ];
    }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Employe) {
            $hashedPassword = $this->hasher->hashPassword($entityInstance, $entityInstance->getPassword());
            $entityInstance->setPassword($hashedPassword);
            $entityInstance->setRoles(['ROLE_EMPLOYE']);
        }

        parent::persistEntity($em, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $em, $entityInstance): void
    {
        // Mot de passe déjà haché => on ne le modifie que si l’admin le change manuellement
        if ($entityInstance instanceof Employe && $entityInstance->getPassword()) {
            $hashedPassword = $this->hasher->hashPassword($entityInstance, $entityInstance->getPassword());
            $entityInstance->setPassword($hashedPassword);
        }

        parent::updateEntity($em, $entityInstance);
    }
}

