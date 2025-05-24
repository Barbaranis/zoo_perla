<?php


namespace App\Controller\Admin;


use App\Service\FirebaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FirebaseMessagesController extends AbstractController
{
    private FirebaseService $firebaseService;


    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }


    #[Route('/admin/firebase/messages', name: 'firebase_messages')]
    public function index(): Response
    {
        $messages = $this->firebaseService->getMessages();


        return $this->render('admin/firebase/messages.html.twig', [
            'messages' => $messages,
        ]);
    }


    #[Route('/admin/firebase/messages/delete/{key}', name: 'delete_firebase_message', methods: ['POST'])]
    public function deleteMessage(string $key): RedirectResponse
    {
        $this->firebaseService->deleteMessage($key);
        $this->addFlash('success', 'Message supprimé avec succès.');


        return $this->redirectToRoute('firebase_messages');
    }
}


