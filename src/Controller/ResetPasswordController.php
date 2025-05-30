<?php


namespace App\Controller;


use App\Entity\Admin;
use App\Form\ResetPasswordRequestType;
use App\Form\ChangePasswordFormType;
use App\Repository\AdminRepository;
use App\Service\TokenGeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class ResetPasswordController extends AbstractController
{
    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(
        Request $request,
        AdminRepository $adminRepository,
        EntityManagerInterface $em,
        TokenGeneratorService $tokenService
    ): Response {
        $form = $this->createForm(ResetPasswordRequestType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $admin = $adminRepository->findOneBy(['email' => $form->get('email')->getData()]);


            if ($admin) {
                $token = $tokenService->generateToken();
                $admin->setResetToken($token);
                $em->flush();


                $this->addFlash('success', 'Lien de réinitialisation : ' .
                    $this->generateUrl('app_reset_password', ['token' => $token], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL));
            } else {
                $this->addFlash('danger', 'Aucun compte administrateur trouvé avec cet e-mail.');
            }
        }


        return $this->render('security/forgot_password.html.twig', [
            'requestForm' => $form->createView()
        ]);
    }


    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function resetPassword(
        string $token,
        Request $request,
        AdminRepository $adminRepository,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $admin = $adminRepository->findOneBy(['resetToken' => $token]);


        if (!$admin) {
            $this->addFlash('danger', 'Token invalide.');
            return $this->redirectToRoute('app_forgot_password');
        }


        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $admin->setPassword(
                $passwordHasher->hashPassword($admin, $form->get('plainPassword')->getData())
            );
            $admin->setResetToken(null);
            $em->flush();


            $this->addFlash('success', 'Mot de passe modifié avec succès !');
            return $this->redirectToRoute('login');
        }


        return $this->render('security/reset_password.html.twig', [
            'resetForm' => $form->createView()
        ]);
    }
}





