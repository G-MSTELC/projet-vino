<?php

namespace App\Controller;

use App\Entity\Cellier;
use App\Form\CellierType;
use App\Repository\CellierRepository;
use App\Repository\BouteilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CellierController extends AbstractController
{
    private $entityManager;
    private $cellierRepository;
    private $bouteilleRepository;

    public function __construct(EntityManagerInterface $entityManager, CellierRepository $cellierRepository, BouteilleRepository $bouteilleRepository)
    {
        $this->entityManager = $entityManager;
        $this->cellierRepository = $cellierRepository;
        $this->bouteilleRepository = $bouteilleRepository;
    }

    /**
     * @Route("/celliers", name="cellier_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $searchTerm = $request->query->get('searchTerm');
        $sortingCriteria = $request->query->get('sortingCriteria');
        $filters = $request->query->all();

        unset($filters['searchTerm'], $filters['sortingCriteria']);

        $celliers = $this->cellierRepository->findAllWithFilters($searchTerm, $sortingCriteria, $filters);

        return $this->render('cellier/index.html.twig', [
            'celliers' => $celliers,
            'searchTerm' => $searchTerm,
            'sortingCriteria' => $sortingCriteria,
            'filters' => $filters,
        ]);
    }

    /**
     * @Route("/cellier/create", name="cellier_create", methods={"GET", "POST"})
     */
    public function create(Request $request): Response
    {
        $cellier = new Cellier();
        $form = $this->createForm(CellierType::class, $cellier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Initialiser le prix_total à 0 lors de la création du cellier
            $cellier->setPrixTotal(0);

            $this->entityManager->persist($cellier);
            $this->entityManager->flush();

            $this->addFlash('success', 'Le cellier a été créé avec succès.');

            return $this->redirectToRoute('cellier_index');
        }

        return $this->render('cellier/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/cellier/{id}", name="cellier_show")
     */
    public function show(Cellier $cellier): Response
    {
        $bouteilleCelliers = $cellier->getBouteilleCelliers();
        $prixTotal = 0;

        foreach ($bouteilleCelliers as $bouteilleCellier) {
            $prixTotal += $bouteilleCellier->getBouteille()->getPrix() * $bouteilleCellier->getQuantite();
        }

        return $this->render('cellier/show.html.twig', [
            'cellier' => $cellier,
            'prixTotal' => $prixTotal,
        ]);
    }

    /**
     * @Route("/cellier/{id}/edit", name="cellier_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Cellier $cellier): Response
    {
        $form = $this->createForm(CellierType::class, $cellier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Le cellier a été modifié avec succès.');

            return $this->redirectToRoute('cellier_show', ['id' => $cellier->getId()]);
        }

        return $this->render('cellier/edit.html.twig', [
            'cellier' => $cellier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/cellier/{id}/delete", name="cellier_delete", methods={"POST"})
     */
    public function delete(Request $request, Cellier $cellier): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cellier->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($cellier);
            $this->entityManager->flush();

            $this->addFlash('success', 'Le cellier a été supprimé avec succès.');
        }

        return $this->redirectToRoute('cellier_index');
    }

    /**
 * @Route("/celliers/{cellierId}/bouteilles/search", name="cellier_bouteille_search")
 */
public function search(Request $request, int $cellierId): Response
{
    $searchTerm = $request->query->get('search', '');
    $bouteilles = $this->bouteilleRepository->findBySearchTermAndCellier($searchTerm, $cellierId);

    return $this->render('cellier/show-search.html.twig', [
        'bouteilles' => $bouteilles,
        'cellierId' => $cellierId,
        'searchTerm' => $searchTerm,
    ]);
}
    /**
     * @Route("/celliers/{cellierId}/bouteilles/sorting", name="cellier_bouteille_sorting")
     */
    public function sorting(Request $request, int $cellierId): Response
    {
        dump($request->query->all());
        $sortingCriteria = $request->query->get('sortingCriteria', 'name_asc');
        $searchTerm = $request->query->get('search', '');
        $filters = $request->query->all('filters');

        $bouteilles = $this->bouteilleRepository->findBySortingCriteriaAndCellier($sortingCriteria, $searchTerm, $filters, $cellierId);

        return $this->render('cellier/show-sorting.html.twig', [
            'bouteilles' => $bouteilles,
            'cellierId' => $cellierId,
            'sortingCriteria' => $sortingCriteria,
            'searchTerm' => $searchTerm,
            'filters' => $filters,
        ]);
    }

    /**
     * @Route("/cellier/{id}/search", name="cellier_bouteille_search_id")
     */
    public function searchBouteille(Request $request, int $id): Response
    {
        // Utilisation du repository via l'injection de dépendance dans le constructeur
        $bouteilles = $this->bouteilleRepository->findBySearchTermAndCellier($request->query->get('search', ''), $id);

        return $this->render('cellier/show-search.html.twig', [
            'bouteilles' => $bouteilles,
            'searchTerm' => $request->query->get('search', ''),
            'cellierId' => $id,
        ]);
    }
}
