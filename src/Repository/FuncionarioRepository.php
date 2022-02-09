<?php

namespace App\Repository;

use App\Entity\Funcionario;
use App\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Funcionario|null find($id, $lockMode = null, $lockVersion = null)
 * @method Funcionario|null findOneBy(array $criteria, array $orderBy = null)
 * @method Funcionario[]    findAll()
 * @method Funcionario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FuncionarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Funcionario::class);
    }

    // /**
    //  * @return Funcionario[] Returns an array of Funcionario objects
    //  */
    public function findAllAndJoin()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT funcionario.id, funcionario.nome, funcionario.cpf, funcionario.data_nasc, cargo.nome cargo, user.username
            FROM funcionario
            INNER JOIN cargo ON funcionario.cod_cargo_id = cargo.id
            INNER JOIN user ON funcionario.cod_user_id = user.id;
        ';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchAllAssociative();
    }

    /*
    public function findOneBySomeField($value): ?Funcionario
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
