<?php

namespace App\Repository;

use App\Entity\Cellier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\QueryException;

class CellierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cellier::class);
    }

    /**
     * @param string|null $searchTerm
     * @param string|null $sortingCriteria
     * @param array $filters
     * @return Cellier[]
     */
    public function findAllWithFilters(?string $searchTerm, ?string $sortingCriteria, array $filters): array
    {
        try {
            $queryBuilder = $this->createQueryBuilder('c');

            // Apply search term filter
            if ($searchTerm) {
                $queryBuilder
                    ->andWhere('c.nom LIKE :searchTerm')
                    ->setParameter('searchTerm', '%' . $searchTerm . '%');
            }

            // Apply additional filters
            foreach ($filters as $filter => $value) {
               
                $queryBuilder->andWhere('c.' . $filter . ' = :' . $filter)
                    ->setParameter($filter, $value);
            }

            // Apply sorting criteria
            switch ($sortingCriteria) {
                case 'nom_asc':
                    $queryBuilder->orderBy('c.nom', 'ASC');
                    break;
                case 'nom_desc':
                    $queryBuilder->orderBy('c.nom', 'DESC');
                    break;
               
            }

            return $queryBuilder->getQuery()->getResult();
        } catch (QueryException $e) {
   
            return [];
        }
    }

    /**
     * Finds celliers with the count of bouteilles.
     *
     * @param $user
     * @return Cellier[]
     */
    public function findCelliersWithBouteillesCount($user): array
    {
        try {
            return $this->createQueryBuilder('c')
                ->leftJoin('c.bouteilles', 'b')
                ->addSelect('COUNT(b.id) as bouteilles_count')
                ->where('c.user = :user')
                ->setParameter('user', $user)
                ->groupBy('c.id')
                ->getQuery()
                ->getResult();
        } catch (QueryException $e) {
          
            return [];
        }
    }
}
