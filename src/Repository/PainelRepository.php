<?php

namespace App\Repository;

use App\Entity\Painel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Painel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Painel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Painel[]    findAll()
 * @method Painel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PainelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Painel::class);
    }

    public function findAllDesc()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT *
            FROM painel
            ORDER BY painel.id ASC;
        ';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchAllAssociative();
    }

    /*
    public function findOneBySomeField($value): ?Painel
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
