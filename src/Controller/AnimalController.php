<?php


// src/Controller/AnimalController.php
namespace App\Controller;


use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AnimalController extends AbstractController
{
    #[Route('/animaux', name: 'public_animals')]
    public function index(AnimalRepository $animalRepository): Response
    {
        return $this->render('animal/public_index.html.twig', [
            'animaux' => $animalRepository->findAll(),
        ]);
    }



    #[Route('/enclos/{id}/animaux', name: 'animaux_par_enclos')]
public function animauxParEnclos(int $id, AnimalRepository $animalRepository): Response
{
    $animaux = $animalRepository->findBy(['enclos' => $id]);


    return $this->render('animal/par_enclos.html.twig', [
        'animaux' => $animaux,
    ]);
}



}


