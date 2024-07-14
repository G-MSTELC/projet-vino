<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserPermissionController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    #[Route('/admin/users', name: 'user_permission_index')]
    public function index(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('user_permission/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/users/edit/{id}', name: 'user_permission_edit')]
    public function edit(Request $request, User $user): Response
    {
        if ($request->isMethod('POST')) {
            $roles = $request->request->all('roles');
            if (!is_array($roles)) {
                $roles = [];
            }
            $user->setRoles($roles);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('user_permission_index');
        }

        return $this->render('user_permission/edit.html.twig', [
            'user' => $user,
        ]);
    }
}
