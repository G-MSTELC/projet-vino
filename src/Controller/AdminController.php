<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function index(): Response
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        $users = $userRepository->findAll();

        return $this->render('admin/index-users.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/user/create", name="admin_create_user")
     */
    public function manageUser(Request $request, User $user = null): Response
    {
        $isNew = !$user;
        if ($isNew) {
            $user = new User();
        }

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encodez le mot de passe si nécessaire
            

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', $isNew ? 'Utilisateur créé avec succès.' : 'Utilisateur modifié avec succès.');

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/' . ($isNew ? 'create-user' : 'edit-user') . '.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/admin/user/{id}", name="admin_show_user", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('admin/show-user.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/admin/user/{id}/edit", name="admin_edit_user", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        return $this->manageUser($request, $user);
    }

    /**
     * @Route("/admin/user/{id}/delete", name="admin_delete_user", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        }

        return $this->redirectToRoute('admin_users');
    }
}
