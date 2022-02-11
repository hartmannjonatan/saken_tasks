<?php

namespace App\Repository;

use App\Entity\Cargo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cargo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cargo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cargo[]    findAll()
 * @method Cargo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CargoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cargo::class);
    }

    // /**
    //  * @return Cargo[] Returns an array of Cargo objects
    //  */
    public function findAllAndFunc()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT *
            FROM cargo
            ORDER BY cargo.id DESC;
        ';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchAllAssociative();
    }

    /*
    public function findOneBySomeField($value): ?Cargo
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
