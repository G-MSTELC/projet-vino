<?php


namespace App\Repository;

use App\Entity\BouteilleCellier;
use App\Entity\Cellier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BouteilleCellierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BouteilleCellier::class);
    }

    /**
     * Retourne toutes les bouteilles associées à un cellier donné.
     *
     * @param Cellier $cellier
     * @return BouteilleCellier[]
     */
    public function findBouteillesByCellier(Cellier $cellier): array
    {
        return $this->createQueryBuilder('bc')
            ->andWhere('bc.cellier = :cellier')
            ->setParameter('cellier', $cellier)
            ->getQuery()
            ->getResult();
    }
}
