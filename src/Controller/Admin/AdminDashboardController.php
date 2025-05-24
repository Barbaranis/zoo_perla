<?php


namespace App\Controller\Admin;


use App\Entity\Admin;
use App\Entity\Animal;
use App\Entity\Enclos;
use App\Entity\Employe;
use App\Entity\Visite;
use App\Controller\Admin\AdminsCrudController;
use App\Controller\Admin\AnimalCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[AdminDashboard]
class AdminDashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        $url = $this->container->get(AdminUrlGenerator::class)
            ->setController(AnimalCrudController::class)
            ->generateUrl();


        return $this->redirect($url);
    }


    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Zoo Arcadia');
    }


    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');


        yield MenuItem::section('Gestion du Zoo');
        yield MenuItem::linkToCrud('Animaux', 'fas fa-paw', Animal::class);
        yield MenuItem::linkToCrud('Enclos', 'fas fa-tree', Enclos::class);
        yield MenuItem::linkToCrud('Employés', 'fas fa-user', Employe::class);
        yield MenuItem::linkToCrud('Visites', 'fas fa-ticket-alt', Visite::class);


        yield MenuItem::section('Administration');
        yield MenuItem::linkToCrud('Administrateurs', 'fas fa-user-shield', Admin::class)
            ->setController(AdminsCrudController::class);


        yield MenuItem::section('Firebase');
        yield MenuItem::linkToRoute('Messages Firebase', 'fas fa-comment', 'firebase_messages');
      


        yield MenuItem::section('Site');
        yield MenuItem::linkToRoute('Retour au site', 'fas fa-arrow-left', 'home');
        yield MenuItem::linkToLogout('Déconnexion', 'fas fa-sign-out-alt');
    }
}

