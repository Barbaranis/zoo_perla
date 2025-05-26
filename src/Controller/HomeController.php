<?php


namespace App\Controller;


use App\Form\AvisType;
use App\Service\FirebaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;




class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, FirebaseService $firebase, FormFactoryInterface $formFactory): Response
    {
        $response = new Response();


        // COOKIE de visite
        if (!$request->cookies->get('visite_zoo_arcadia')) {
            $response->headers->setCookie(
                Cookie::create('visite_zoo_arcadia')
                    ->withValue('true')
                    ->withExpires(strtotime('+1 year'))
            );
        }


        // Avis Firebase
        $messages = $firebase->getMessages();


        // Formulaire avis
        $form = $formFactory->create(AvisType::class);


        // Événements
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


        // Rendu de la vue
        $response->setContent($this->renderView('home/index.html.twig', [
            'messages' => $messages,
            'avisForm' => $form->createView(),
            'events' => $events,
        ]));


        return $response;
    }


    #[Route('/submit-avis', name: 'submit_avis', methods: ['POST'])]
    public function submitAvis(
        Request $request,
        FirebaseService $firebase,
        RateLimiterFactory $submitAvisLimiter,
        FormFactoryInterface $formFactory
    ): RedirectResponse {
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


    #[Route('/reservation/{id}', name: 'reservation')]
    public function reservation(int $id): Response
    {
        return $this->render('reservation.html.twig', [
            'id' => $id
        ]);
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

