<?php


namespace App\Controller;

use App\Entity\Bouteille;
use App\Entity\Cellier;
use App\Entity\BouteilleCellier;
use App\Form\BouteilleType;
use App\Repository\BouteilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BouteilleController extends AbstractController
{
    private $entityManager;
    private $paginator;
    private $bouteilleRepository;

    public function __construct(EntityManagerInterface $entityManager, PaginatorInterface $paginator, BouteilleRepository $bouteilleRepository)
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
        $this->bouteilleRepository = $bouteilleRepository;
    }

    /**
     * @Route("/bouteilles", name="bouteille_index")
     */
    public function index(Request $request): Response
    {
        $query = $this->entityManager->getRepository(Bouteille::class)->createQueryBuilder('b')->getQuery();

        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        $celliers = $this->entityManager->getRepository(Cellier::class)->findBy(['user' => $this->getUser()]);

        return $this->render('bouteille/index.html.twig', [
            'pagination' => $pagination,
            'celliers' => $celliers,
        ]);
    }

    /**
     * @Route("/bouteilles/{id}", name="bouteille_show", requirements={"id"="\d+"})
     */
    public function show(Bouteille $bouteille): Response
    {
        return $this->render('bouteille/show.html.twig', [
            'bouteille' => $bouteille,
        ]);
    }

    /**
     * @Route("/bouteilles/create", name="bouteille_create")
     */
    public function create(Request $request): Response
    {
        $bouteille = new Bouteille();
        $form = $this->createForm(BouteilleType::class, $bouteille);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($bouteille);
            $this->entityManager->flush();

            return $this->redirectToRoute('bouteille_index');
        }

        return $this->render('bouteille/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/bouteilles/{id}/edit", name="bouteille_edit", requirements={"id"="\d+"})
     */
    public function edit(Request $request, Bouteille $bouteille): Response
    {
        $form = $this->createForm(BouteilleType::class, $bouteille);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('bouteille_index');
        }

        return $this->render('bouteille/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/bouteilles/{id}/delete", name="bouteille_delete", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function delete(Request $request, Bouteille $bouteille): Response
    {
        if ($this->isCsrfTokenValid('delete' . $bouteille->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($bouteille);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('bouteille_index');
    }

    /**
     * @Route("/bouteilles/search", name="bouteille_search")
     */
    public function search(Request $request): Response
    {
        $searchTerm = $request->query->get('search');
        $bouteilles = $this->bouteilleRepository->findBySearchTerm($searchTerm);

        return $this->render('bouteille/show-search.html.twig', [
            'bouteilles' => $bouteilles,
        ]);
    }

    /**
     * @Route("/bouteilles/sorting", name="bouteille_sorting")
     */
    public function sorting(Request $request): Response
    {
       // throw $this->createNotFoundException('arret');
      
        $sortingCriteria = $request->query->get('sort', 'name_asc');
        $searchTerm = $request->query->get('search', null);
        //$filters = $request->query->all('filters');
        $filters = $request->query->all();
       //*************
      // $filters=[];
       //$filters['color'] = $request->query->get('color', null);
       //$filters['format'] = $request->query->get('format', null);
       
       //*************
        dump($request->query->all());
        dump($sortingCriteria,$searchTerm,$filters);

        $bouteilles = $this->bouteilleRepository->findBySortingCriteria($sortingCriteria, $searchTerm, $filters);

        return $this->render('bouteille/show-sorting.html.twig', [
            'bouteilles' => $bouteilles,
        ]);
    }

    /**
     * @Route("/cellier/{id}/bouteille/select", name="bouteille_select")
     */
    public function select(int $id): Response
    {
        $cellier = $this->entityManager->getRepository(Cellier::class)->find($id);

        if (!$cellier) {
            throw $this->createNotFoundException('Cellier non trouvé.');
        }

        return $this->render('bouteille/select.html.twig', [
            'cellier' => $cellier,
        ]);
    }

    /**
     * @Route("/bouteilles/selectAll/{id}", name="bouteille_select_all", methods={"POST"})
     */
    public function selectAllBouteilles(Request $request, Cellier $cellier): Response
    {
        $bouteilles = $this->bouteilleRepository->findAll();

        foreach ($bouteilles as $bouteille) {
            $bouteilleCellier = new BouteilleCellier();
            $bouteilleCellier->setCellier($cellier);
            $bouteilleCellier->setBouteille($bouteille);
            $this->entityManager->persist($bouteilleCellier);
        }

        $this->entityManager->flush();

        return $this->redirectToRoute('cellier_show', ['id' => $cellier->getId()]);
    }

    /**
     * @Route("/bouteilles/select/{id}", name="bouteille_select_post", methods={"POST"})
     */
    public function selectPost(Request $request, Cellier $cellier): Response
    {
        $bouteilleIds = $request->request->all('bouteilles');
        if (!is_array($bouteilleIds)) {
            $bouteilleIds = [];
        }
        
        foreach ($bouteilleIds as $bouteilleId) {
            if (is_int($bouteilleId) || ctype_digit($bouteilleId)) {
                $bouteilleId = (int)$bouteilleId;
                $bouteille = $this->entityManager->getRepository(Bouteille::class)->find($bouteilleId);
                if ($bouteille instanceof Bouteille) {
                    $bouteilleCellier = new BouteilleCellier();
                    $bouteilleCellier->setCellier($cellier);
                    $bouteilleCellier->setBouteille($bouteille);
                    $this->entityManager->persist($bouteilleCellier);
                }
            }
        }

        $this->entityManager->flush();

        return $this->redirectToRoute('cellier_show', ['id' => $cellier->getId()]);
    }
}
