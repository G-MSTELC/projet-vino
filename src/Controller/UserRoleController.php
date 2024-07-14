<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserRoleController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    #[Route('/admin/users/assign-role/{username}', name: 'assign_role', methods: ['POST'])]
    public function assignRole(Request $request, string $username): Response
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);
        if (!$user) {
            return $this->redirectToRoute('user_permission_index', [
                'error' => 'Utilisateur non trouvé.',
            ]);
        }

        $roleName = $request->request->get('role');
        if (!$roleName) {
            return $this->redirectToRoute('user_permission_index', [
                'error' => 'Rôle non trouvé.',
            ]);
        }

        $roles = $user->getRoles();
        if (!in_array($roleName, $roles)) {
            $roles[] = $roleName;
            $user->setRoles($roles);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('user_permission_index', [
            'success' => 'Rôle attribué avec succès.',
        ]);
    }

    #[Route('/admin/users/revoke-role/{username}', name: 'revoke_role', methods: ['POST'])]
    public function revokeRole(Request $request, string $username): Response
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);
        if (!$user) {
            return $this->redirectToRoute('user_permission_index', [
                'error' => 'Utilisateur non trouvé.',
            ]);
        }

        $roleName = $request->request->get('role');
        if (!$roleName) {
            return $this->redirectToRoute('user_permission_index', [
                'error' => 'Rôle non trouvé.',
            ]);
        }

        $roles = $user->getRoles();
        if (in_array($roleName, $roles)) {
            $roles = array_diff($roles, [$roleName]);
            $user->setRoles($roles);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('user_permission_index', [
            'success' => 'Rôle révoqué avec succès.',
        ]);
    }
}
