<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/employe', name: 'employe_dashboard')]
#[IsGranted('ROLE_EMPLOYE')]
class EmployeDashboardController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('employe/dashboard.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}

