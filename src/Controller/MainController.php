<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RegistrationFormType;
use App\Form\LoginFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->redirectToRoute('app_login');
    }

    #[Route('/main', name: 'app_main')]
    public function index(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        // Création des formulaires
        $registrationForm = $this->createForm(RegistrationFormType::class)->createView();
        $loginForm = $this->createForm(LoginFormType::class)->createView();

        // Gestion des erreurs de connexion
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        // Ajout d'un message flash de succès
        $this->addFlash('success', 'Opération réussie !');

        // Rendre le template 'main/index.html.twig' avec les données nécessaires
        return $this->render('main/index.html.twig', [
            'registrationForm' => $registrationForm,
            'loginForm' => $loginForm,
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
