<?php


namespace App\Controller;

use App\Entity\BouteilleCellier;
use App\Form\BouteilleCellierType;
use App\Repository\BouteilleCellierRepository;
use App\Repository\BouteilleRepository;
use App\Repository\CellierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BouteilleCellierController extends AbstractController
{
    private $entityManager;
    private $bouteilleRepository;
    private $cellierRepository;

    public function __construct(EntityManagerInterface $entityManager, BouteilleRepository $bouteilleRepository, CellierRepository $cellierRepository)
    {
        $this->entityManager = $entityManager;
        $this->bouteilleRepository = $bouteilleRepository;
        $this->cellierRepository = $cellierRepository;
    }

    /**
     * @Route("/bouteille_cellier", name="bouteille_cellier_index", methods={"GET"})
     */
    public function index(BouteilleCellierRepository $bouteilleCellierRepository): Response
    {
        $bouteilleCelliers = $bouteilleCellierRepository->findAll();

        return $this->render('bouteille_cellier/index.html.twig', [
            'bouteille_celliers' => $bouteilleCelliers,
        ]);
    }

    /**
     * @Route("/bouteille_cellier/create", name="bouteille_cellier_create", methods={"GET", "POST"})
     */
    public function create(Request $request): Response
    {
        $bouteilleCellier = new BouteilleCellier();
        $form = $this->createForm(BouteilleCellierType::class, $bouteilleCellier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $bouteilleId = $data->getBouteille()->getId();
            $cellierId = $data->getCellier()->getId();

            $bouteille = $this->bouteilleRepository->find($bouteilleId);
            $cellier = $this->cellierRepository->find($cellierId);

            if (!$bouteille || !$cellier) {
                throw $this->createNotFoundException('Bouteille or Cellier not found');
            }

            $bouteilleCellier->setBouteille($bouteille);
            $bouteilleCellier->setCellier($cellier);
            $bouteilleCellier->setQuantite($data->getQuantite());

            $this->entityManager->persist($bouteilleCellier);
            $this->entityManager->flush();

            return $this->redirectToRoute('bouteille_cellier_index');
        }

        return $this->render('bouteille_cellier/create.html.twig', [
            'bouteille_cellier' => $bouteilleCellier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/bouteille_cellier/{id}", name="bouteille_cellier_show", methods={"GET"})
     */
    public function show(BouteilleCellier $bouteilleCellier): Response
    {
        return $this->render('bouteille_cellier/show.html.twig', [
            'bouteille_cellier' => $bouteilleCellier,
        ]);
    }

    /**
     * @Route("/bouteille_cellier/{id}/edit", name="bouteille_cellier_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, BouteilleCellier $bouteilleCellier): Response
    {
        $form = $this->createForm(BouteilleCellierType::class, $bouteilleCellier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('bouteille_cellier_index');
        }

        return $this->render('bouteille_cellier/edit.html.twig', [
            'bouteille_cellier' => $bouteilleCellier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/bouteille_cellier/{id}", name="bouteille_cellier_delete", methods={"POST"})
     */
    public function delete(Request $request, BouteilleCellier $bouteilleCellier): Response
    {
        if ($this->isCsrfTokenValid('delete' . $bouteilleCellier->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($bouteilleCellier);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('bouteille_cellier_index');
    }

    /**
     * @Route("/cellier/{id}/select_bouteille", name="bouteille_cellier_select_bouteille", methods={"GET"})
     */
    public function selectBouteille(int $id, CellierRepository $cellierRepository, BouteilleRepository $bouteilleRepository): Response
    {
        $cellier = $cellierRepository->find($id);

        if (!$cellier) {
            throw $this->createNotFoundException('Cellier not found');
        }

        // Récupérer toutes les bouteilles disponibles
        $bouteilles = $bouteilleRepository->findAll();

        return $this->render('cellier/select_bouteille.html.twig', [
            'cellier' => $cellier,
            'bouteilles' => $bouteilles,
        ]);
    }

    /**
     * @Route("/cellier/{id}/ajouter_bouteille", name="bouteille_cellier_ajouter_bouteille", methods={"POST"})
     */
    public function ajouterBouteille(Request $request, int $id): Response
    {
        $bouteilleId = $request->request->get('bouteille_id');
        $quantite = $request->request->get('quantite');

        if (!$bouteilleId || !$quantite) {
            throw $this->createNotFoundException('No bouteille id or quantity provided');
        }

        $bouteille = $this->bouteilleRepository->find($bouteilleId);
        if (!$bouteille) {
            throw $this->createNotFoundException('No bouteille found for id ' . $bouteilleId);
        }

        $cellier = $this->cellierRepository->find($id);
        if (!$cellier) {
            throw $this->createNotFoundException('No cellier found for id ' . $id);
        }

        $bouteilleCellier = new BouteilleCellier();
        $bouteilleCellier->setBouteille($bouteille);
        $bouteilleCellier->setCellier($cellier);
        $bouteilleCellier->setQuantite($quantite);

        $this->entityManager->persist($bouteilleCellier);
        $this->entityManager->flush();

        return $this->redirectToRoute('cellier_show', ['id' => $id]);
    }
}

