<?php

namespace App\Controller;

use App\Repository\AnimalRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminStatsController extends AbstractController

{
    #[Route('/admin/stats', name: 'admin_stats')]
    public function index(AnimalRepository $animalRepo, UserRepository $userRepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $animalCount = $animalRepo->count([]);
        $userCount = $userRepo->count([]);

        return $this->render('admin/dashboard.html.twig', [
            'animal_count' => $animalCount,
            'user_count' => $userCount,
        ]);
    }
}

