<?php

namespace App\Repository;

use App\Entity\BouteillePersonnalisee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BouteillePersonnalisee|null find($id, $lockMode = null, $lockVersion = null)
 * @method BouteillePersonnalisee|null findOneBy(array $criteria, array $orderBy = null)
 * @method BouteillePersonnalisee[]    findAll()
 * @method BouteillePersonnalisee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BouteillePersonnaliseeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BouteillePersonnalisee::class);
    }

  
}
