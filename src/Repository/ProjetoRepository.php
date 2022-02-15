<?php

namespace App\Repository;

use App\Entity\Projeto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Projeto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Projeto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Projeto[]    findAll()
 * @method Projeto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjetoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Projeto::class);
    }

    public function findAllDesc()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT *
            FROM projeto
            ORDER BY projeto.id DESC
            LIMIT 5;
        ';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchAllAssociative();
    }

    public function findSlugAndJoin(string $slug)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT projeto.id, projeto.nome, projeto.cliente, projeto.created_at, projeto.updated_at, projeto.descricao, projeto.slug, projeto.url_img_cover, funcionario.nome as funcionario
            FROM projeto
            INNER JOIN funcionario ON projeto.coordenador_id = funcionario.id
            WHERE projeto.slug = :slug
        ';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['slug' => $slug]);

        return $resultSet->fetchAllAssociative();
    }

    /*
    public function findOneBySomeField($value): ?Projeto
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
