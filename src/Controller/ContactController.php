<?php


namespace App\Controller;


use App\Form\ContactType;
use App\Service\FirebaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact', methods: ['GET', 'POST'])]
    public function index(Request $request, FirebaseService $firebaseService, ParameterBagInterface $params): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();


            if (!$request->request->get('consentement')) {
                $this->addFlash('error', 'Vous devez accepter le traitement de vos données.');
                return $this->redirectToRoute('contact');
            }


            $firebaseService->saveMessage([
                'nom' => $data['nom'],
                'email' => $data['email'],
                'telephone' => $data['telephone'],
                'objet' => $data['objet'],
                'message' => $data['message'],
                'date' => (new \DateTime())->format('Y-m-d H:i:s'),
            ]);


            $this->addFlash('success', 'Merci pour votre message ! Il a bien été envoyé.');
            return $this->redirectToRoute('contact');
        }


        return $this->render('contact.html.twig', [
            'form' => $form->createView(),
            'recaptcha_site_key' => $params->get('recaptcha_site_key')
        ]);
    }
}

