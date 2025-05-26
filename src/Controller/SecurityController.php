<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\CaptchaOnlyType;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // Récupère les éventuelles erreurs de connexion
       

    $error = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();


    $captchaForm = $this->createForm(CaptchaOnlyType::class);
    $captchaForm->handleRequest($request);


    return $this->render('login.html.twig', [
        'last_username' => $lastUsername,
        'error' => $error,
        'captchaForm' => $captchaForm->createView(),
        'recaptcha_site_key' => $_ENV['RECAPTCHA_SITE_KEY'], // <- c’est ça qu’il te faut
    ]);
    
    
}



    #[Route('/logout', name: 'logout')]
    public function logout(): void
    {
        // Cette méthode peut rester vide, Symfony gère la déconnexion automatiquement
        throw new \LogicException('Cette méthode peut rester vide. Elle est interceptée par le pare-feu de sécurité.');
    }
}

