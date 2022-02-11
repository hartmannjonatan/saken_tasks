<?php

namespace App\Repository;

use App\Entity\Gerente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Gerente|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gerente|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gerente[]    findAll()
 * @method Gerente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GerenteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gerente::class);
    }

    // /**
    //  * @return Gerente[] Returns an array of Gerente objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Gerente
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
