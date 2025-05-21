<?php

namespace App\Controller;


use App\Entity\Admin;
use App\Repository\AdminRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/api/admins')]
class AdminApiController extends AbstractController
{
    #[Route('', name: 'admin_index', methods: ['GET'])]
    public function index(AdminRepository $repo): JsonResponse
    {
        $admins = $repo->findAll();
        $data = array_map(fn($admin) => [
            'id' => $admin->getId(),
            'email' => $admin->getEmail(),
            'roles' => $admin->getRoles()
        ], $admins);


        return $this->json($data);
    }


    #[Route('', name: 'admin_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $admin = new Admin();
        $admin->setEmail($data['email']);
        $admin->setRoles($data['roles'] ?? ['ROLE_ADMIN']);
        $admin->setPassword($hasher->hashPassword($admin, $data['password']));
        $em->persist($admin);
        $em->flush();


        return $this->json(['status' => 'admin created'], 201);
    }


    #[Route('/{id}', name: 'admin_show', methods: ['GET'])]
    public function show(Admin $admin): JsonResponse
    {
        return $this->json([
            'id' => $admin->getId(),
            'email' => $admin->getEmail(),
            'roles' => $admin->getRoles()
        ]);
    }


    #[Route('/{id}', name: 'admin_update', methods: ['PUT'])]
    public function update(
        Request $request,
        Admin $admin,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        if (isset($data['email'])) $admin->setEmail($data['email']);
        if (isset($data['roles'])) $admin->setRoles($data['roles']);
        if (isset($data['password'])) {
            $admin->setPassword($hasher->hashPassword($admin, $data['password']));
        }
        $em->flush();


        return $this->json(['status' => 'admin updated']);
    }


    #[Route('/{id}', name: 'admin_delete', methods: ['DELETE'])]
    public function delete(Admin $admin, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($admin);
        $em->flush();


        return $this->json(['status' => 'admin deleted']);
    }
}

