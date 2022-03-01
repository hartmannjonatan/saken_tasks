<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    // /**
    //  * @return Task[] Returns an array of Task objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    public function findAllByFunc(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT task.id, task.nome, task.descricao, task.conclued_at, task.final_date, task.created_at, funcionario.nome funcionario, classificacao.nome classificacao, tipo.nome tipo, tipo.grau_urgencia, projeto.id as projeto, projeto.nome as nome_projeto
            FROM task
            INNER JOIN funcionario ON task.cod_func_id = funcionario.id
            INNER JOIN classificacao ON task.cod_class_id = classificacao.id
            INNER JOIN tipo ON task.tipo_id = tipo.id
            INNER JOIN projeto ON task.projeto_id = projeto.id
            WHERE task.cod_func_id = :id
            ORDER BY tipo.grau_urgencia ASC;
        ';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id]);

        return $resultSet->fetchAllAssociative();
    }

    public function findAllByProjeto(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT task.id, task.nome, task.descricao, task.conclued_at, task.final_date, task.created_at, funcionario.nome funcionario, classificacao.nome classificacao, tipo.nome tipo, tipo.grau_urgencia
            FROM task
            INNER JOIN funcionario ON task.cod_func_id = funcionario.id
            INNER JOIN classificacao ON task.cod_class_id = classificacao.id
            INNER JOIN tipo ON task.tipo_id = tipo.id
            WHERE task.projeto_id = :id
            ORDER BY tipo.grau_urgencia ASC;
        ';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id]);

        return $resultSet->fetchAllAssociative();
    }

    public function findByClass(int $id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT task.id
            FROM task
            WHERE task.cod_class_id = :id;
        ';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id]);

        return $resultSet->fetchAllAssociative();
    }
}
