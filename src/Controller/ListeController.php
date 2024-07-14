<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Form\ListeType;
use App\Repository\ListeRepository;
use App\Repository\BouteilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/liste")
 */
class ListeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="liste_index", methods={"GET"})
     */
    public function index(ListeRepository $listeRepository): Response
    {
        $user = $this->getUser();
        $listes = $listeRepository->findBy(['user' => $user]);

        return $this->render('liste/index.html.twig', [
            'listes' => $listes,
        ]);
    }

    /**
     * @Route("/create", name="liste_create", methods={"GET","POST"})
     */
    public function create(Request $request): Response
    {
        $liste = new Liste();
        $form = $this->createForm(ListeType::class, $liste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $liste->setUser($this->getUser());
            $this->entityManager->persist($liste);
            $this->entityManager->flush();

            return $this->redirectToRoute('liste_index');
        }

        return $this->render('liste/create.html.twig', [
            'liste' => $liste,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="liste_show", methods={"GET"})
     */
    public function show($id, ListeRepository $listeRepository, BouteilleRepository $bouteilleRepository): Response
    {
        $liste = $listeRepository->find($id);

        if (!$liste) {
            return $this->redirectToRoute('liste_index');
        }

        $bouteilles = $bouteilleRepository->findBy(['liste' => $liste]);

        return $this->render('liste/show.html.twig', [
            'liste' => $liste,
            'bouteilles' => $bouteilles,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="liste_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, $id, ListeRepository $listeRepository): Response
    {
        $liste = $listeRepository->find($id);

        if (!$liste) {
            return $this->redirectToRoute('liste_index');
        }

        $form = $this->createForm(ListeType::class, $liste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('liste_index');
        }

        return $this->render('liste/edit.html.twig', [
            'liste' => $liste,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="liste_delete", methods={"POST"})
     */
    public function delete(Request $request, $id, ListeRepository $listeRepository): Response
    {
        $liste = $listeRepository->find($id);

        if ($liste && $this->isCsrfTokenValid('delete'.$liste->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($liste);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('liste_index');
    }
}
