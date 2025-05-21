<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FirebaseController extends AbstractController
{
    private string $firebaseUrl = 'https://zooarcadia-420b9-default-rtdb.europe-west1.firebasedatabase.app';

    public function __construct(private readonly HttpClientInterface $httpClient)
    {
    }

    #[Route('/admin/firebase/messages', name: 'firebase_messages')]
    public function messages(): Response
    {
        $response = $this->httpClient->request('GET', $this->firebaseUrl . '/messages.json');
        $messages = $response->toArray();

        return $this->render('admin/firebase_messages.html.twig', [
            'messages' => $messages,
        ]);
    }

    #[Route('/admin/firebase/comments', name: 'firebase_comments')]
    public function comments(): Response
    {
        $response = $this->httpClient->request('GET', $this->firebaseUrl . '/comments.json');
        $comments = $response->toArray();

        return $this->render('admin/firebase_comments.html.twig', [
            'comments' => $comments,
        ]);
    }
}

