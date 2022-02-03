<?php

namespace App\Repository;

use App\Entity\Classificacao;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Classificacao|null find($id, $lockMode = null, $lockVersion = null)
 * @method Classificacao|null findOneBy(array $criteria, array $orderBy = null)
 * @method Classificacao[]    findAll()
 * @method Classificacao[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassificacaoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Classificacao::class);
    }

    // /**
    //  * @return Classificacao[] Returns an array of Classificacao objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Classificacao
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
