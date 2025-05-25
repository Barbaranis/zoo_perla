<?php

namespace App\Controller;

use App\Form\AvisType;
use App\Service\FirebaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(FirebaseService $firebase, FormFactoryInterface $formFactory): Response
    {
        $messages = $firebase->getMessages();
        $form = $formFactory->create(AvisType::class);
    
    
        $events = [
            [
                'id' => 1,
                'title' => 'Fête du printemps',
                'description' => 'Musique, magie florale, et animations pour toute la famille.',
                'date' => new \DateTime('2025-09-18'),
                'cta' => 'Réserver',
            ],
            [
                'id' => 2,
                'title' => 'Soirée nocturne magique',
                'description' => 'Le zoo s’illumine pour une nuit féérique.',
                'date' => new \DateTime('2025-06-24'),
                'cta' => 'Réserver',
            ],
            [
                'id' => 3,
                'title' => 'Ateliers créatifs enfants',
                'description' => 'Activités manuelles tous les jours à 11h.',
                'date' => null,
                'cta' => 'Participer',
            ],
        ];
        
        
        
    
    
        return $this->render('home/index.html.twig', [
            'messages' => $messages,
            'avisForm' => $form->createView(),
            'events' => $events,
        ]);
    }
    
    
    
    
    
    #[Route('/reservation/{id}', name: 'reservation')]
public function reservation(int $id): Response
{
    return $this->render('reservation.html.twig', [
        'id' => $id
    ]);
}






    #[Route('/submit-avis', name: 'submit_avis', methods: ['POST'])]
    public function submitAvis(
        Request $request,
        FirebaseService $firebase,
        RateLimiterFactory $submitAvisLimiter,
        FormFactoryInterface $formFactory
    ): RedirectResponse
    {
        $limiter = $submitAvisLimiter->create($request->getClientIp());

        if (!$limiter->consume(1)->isAccepted()) {
            throw new TooManyRequestsHttpException(60, 'Tu as déjà laissé plusieurs avis ! Reviens dans 1 minute.');
        }

        $form = $formFactory->create(AvisType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $firebase->saveMessage([
                'nom' => 'Anonyme',
                'message' => htmlspecialchars($data['message']),
                'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
            ]);
        }

        return $this->redirectToRoute('home');
    }

    #[Route('/habitats', name: 'habitats')]
    public function habitats(): Response
    {
        return $this->render('habitats.html.twig');
    }

    #[Route('/services', name: 'services')]
    public function services(): Response
    {
        return $this->render('services.html.twig');
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('contact.html.twig');
    }

    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('login.html.twig');
    }

    #[Route('/avis', name: 'avis')]
    public function avis(): Response
    {
        return $this->render('avis.html.twig');
    }


    #[Route('/politique-de-confidentialite', name: 'privacy')]
public function privacy(): Response
{
    return $this->render('static/privacy.html.twig');
}



}

