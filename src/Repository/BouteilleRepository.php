<?php


namespace App\Repository;

use App\Entity\Bouteille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BouteilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bouteille::class);
    }

    public function findById(int $id): ?Bouteille
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // Recherche par terme
    public function findBySearchTerm(string $searchTerm): array
    {
        //dump($searchTerm);
        $qb = $this->createQueryBuilder('b')
            ->where('b.nom LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery();

        return $qb->getResult();
    }

    // Tri par critère spécifique avec recherche et filtres
    public function findBySortingCriteria(string $sortingCriteria, ?string $searchTerm, array $filters): array
    {
        $qb = $this->createQueryBuilder('b');

        // Appliquer le terme de recherche si spécifié
        if ($searchTerm) {
            $qb->andWhere('b.nom LIKE :searchTerm')
               ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        // Appliquer les filtres
        if (!empty($filters)) {
            foreach ($filters as $field => $value) {
                if ($value !== null && $value !== '') {
                    if($field == 'prix_min'){
                        $qb->andWhere('b.prix >= :prix_min')    
                        ->setParameter('prix_min',$value);
                    }elseif ($field == 'prix_max'){
                        $qb->andWhere('b.prix <= :prix_max')    
                        ->setParameter('prix_max',$value);
                    }elseif ($field=='sort'){
                        $sortingCriteria=$value;
                    }
                    else{                    
                    $qb->andWhere("b.$field = :$field")
                       ->setParameter($field, $value);
                    }
                }
            }
        }

        // Appliquer le tri selon le critère spécifié
        switch ($sortingCriteria) {
            case 'name_asc':
                $qb->orderBy('b.nom', 'ASC');
                break;
            case 'name_desc':
                $qb->orderBy('b.nom', 'DESC');
                break;
            case 'price_asc':
                $qb->orderBy('b.prix', 'ASC');
                break;
            case 'price_desc':
                $qb->orderBy('b.prix', 'DESC');
                break;
            // Ajouter d'autres critères de tri si nécessaire
            default:
                // Par défaut, trier par nom ascendant
                $qb->orderBy('b.nom', 'ASC');
        }
dump($qb->getQuery()->getDQL());
        return $qb->getQuery()->getResult();
    }

    // Fonction de recherche par terme et cellier
    public function findBySearchTermAndCellier(string $searchTerm, int $cellierId): array
{
    $queryBuilder = $this->createQueryBuilder('b')
        ->leftJoin('b.bouteilleCelliers', 'bc')
        ->leftJoin('bc.cellier', 'c')
        ->where('c.id = :cellierId')
        ->setParameter('cellierId', $cellierId);

    if (!empty($searchTerm)) {
        // Filter by name starting with the search term
        $queryBuilder
            ->andWhere('b.nom LIKE :searchTerm')
            ->setParameter('searchTerm', $searchTerm . '%');
    }

    return $queryBuilder->getQuery()->getResult();
}

    // Fonction de tri par critères et cellier
    public function findBySortingCriteriaAndCellier(string $sortingCriteria, ?string $searchTerm, array $filters, int $cellierId)
    {
        $qb = $this->createQueryBuilder('b')
            ->innerJoin('b.celliers', 'c')
            ->where('c.id = :cellierId')
            ->setParameter('cellierId', $cellierId);

    // Appliquer les filtres
    if (!empty($filters)) {
        foreach ($filters as $field => $value) {
            if ($value !== null && $value !== '') {
                if($field == 'prix_min'){
                    $qb->andWhere('b.prix >= :prix_min')    
                    ->setParameter('prix_min',$value);
                }elseif ($field == 'prix_max'){
                    $qb->andWhere('b.prix <= :prix_max')    
                    ->setParameter('prix_max',$value);
                }elseif ($field=='sort'){
                    $sortingCriteria=$value;
                }
                else{                    
                $qb->andWhere("b.$field = :$field")
                ->setParameter($field, $value);
                }
            }
        }
    }
// Tri
        switch ($sortingCriteria) {
            case 'name_asc':
                $qb->orderBy('b.nom', 'ASC');
                break;
            case 'name_desc':
                $qb->orderBy('b.nom', 'DESC');
                break;
            case 'year_asc':
                $qb->orderBy('b.annee', 'ASC');
                break;
            case 'year_desc':
                $qb->orderBy('b.annee', 'DESC');
                break;
            
            default:
              
                $qb->orderBy('b.nom', 'ASC');
        }
dump($qb->getQuery()->getDQL());
        return $qb->getQuery()->getResult();
    }

}
