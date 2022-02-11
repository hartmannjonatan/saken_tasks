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
    public function findAllAndJoin()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT gerente.id, gerente.nome, user.username, user.id user_id
            FROM gerente
            INNER JOIN user ON gerente.cod_user_id = user.id
            ORDER BY gerente.id DESC;
        ';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchAllAssociative();
    }

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
