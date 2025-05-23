<?php


// src/Controller/ContactController.php

namespace App\Controller;

use App\Service\FirebaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact', methods: ['GET', 'POST'])]
    public function index(Request $request, FirebaseService $firebaseService): Response
    {
        if ($request->isMethod('POST')) {
            $data = [
                'nom' => $request->request->get('nom'),
                'email' => $request->request->get('email'),
                'telephone' => $request->request->get('telephone'),
                'objet' => $request->request->get('objet'),
                'message' => $request->request->get('message'),
                'date' => (new \DateTime())->format('Y-m-d H:i:s'),
            ];

            $firebaseService->saveMessage($data);
            $this->addFlash('success', 'Merci pour votre message ! Il a bien été envoyé.');
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact.html.twig');
    }
}

