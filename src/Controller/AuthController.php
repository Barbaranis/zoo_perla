<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AuthController extends AbstractController
{
    #[Route('/login_check', name: 'login_check', methods: ['POST'])]
    public function loginCheck(Request $request, HttpClientInterface $httpClient): JsonResponse
    {
        $captcha = $request->request->get('g-recaptcha-response');

        if (!$captcha) {
            return new JsonResponse(['error' => 'Captcha non rempli'], 400);
        }

        $response = $httpClient->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
            'body' => [
                'secret' => $_ENV['GOOGLE_RECAPTCHA_SECRET_KEY'],
                'response' => $captcha,
            ],
        ]);

        $data = $response->toArray();

        if (!$data['success']) {
            return new JsonResponse(['error' => 'Captcha invalide'], 403);
        }

        // Si Captcha OK, continue ici : authentifie l'utilisateur manuellement ou redirige
        return new JsonResponse(['success' => true]);
    }
}
