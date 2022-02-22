<?php

namespace App\Controller;

use App\Entity\Classificacao;
use App\Entity\Task;
use App\Form\Type\TaskTypeEdit;
use App\Form\Type\TaskType;
use App\Repository\ClassificacaoRepository;
use App\Repository\ProjetoRepository;
use App\Repository\TaskRepository;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Length;

class TaskController extends AbstractController
{
    protected $repository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->repository = $taskRepository;
    }

    /**
     * @Route("/newTask", methods="POST")
     */
    public function newTask(Request $request, ManagerRegistry $doctrine, ProjetoRepository $projetoRepository): Response {

        $entityManager = $doctrine->getManager();

        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        $task->setCreatedAt();
        $task->setConcluedAt();

        $class = $form->get("class")->getData();
        $nomeClass = $form->get("nomeClass")->getData();
        if($nomeClass != "nulo"){
            $classificacao = new Classificacao();
            $nomeClass = $form->get("nomeClass")->getData();
            $classificacao->setNome($nomeClass);
            $entityManager->persist($classificacao);
            $task->setCodClass($classificacao);
        } else $task->setCodClass($class);

        $idProjeto = $form->get("idProjeto")->getData();
        $projeto = $projetoRepository->find($idProjeto);
        $task->setProjeto($projeto);

        $entityManager->persist($task);

        $task = $form->getData();

        try {
            $entityManager->persist($task);
            $entityManager->flush();
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                $e->getMessage()
            );
        }
        
        return $this->redirectToRoute('projeto', ['slug' => $projeto->getSlug()]);

    }

    public function newTaskForm(): Response{
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        return $this->render('projeto/_newTask.html.twig', ["form" => $form->createView()]);
    }

    #[Route('/editTask/{idTask}/{idProjeto}', name: 'editTask')]
    public function editTask(int $idTask, int $idProjeto, Request $request, ManagerRegistry $doctrine, ClassificacaoRepository $classificacaoRepository, ProjetoRepository $projetoRepository): Response {

        $entityManager = $doctrine->getManager(); 
        $task = $this->repository->find($idTask);

        $form = $this->createForm(TaskTypeEdit::class, $task);

        $form->handleRequest($request);

        $class = $form->get("class")->getData();
        $classAntiga = $task->getCodClass();
        if($classAntiga != $class){
            $nomeClass = $form->get("nomeClass")->getData();
            if($nomeClass != "nulo"){
                $classificacao = new Classificacao();
                $nomeClass = $form->get("nomeClass")->getData();
                $classificacao->setNome($nomeClass);
                $entityManager->persist($classificacao);
                $task->setCodClass($classificacao);
                $entityManager->persist($task);
            } else $task->setCodClass($class);

            $entityManager->persist($task);
        }

        $entityManager->persist($task);

        $projeto = $projetoRepository->find($idProjeto);

        $task = $form->getData();

        try {
            $entityManager->persist($task);
            $entityManager->flush();
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                $e->getMessage()
            );
        }

        $idClassAntiga = $classAntiga->getId();
        $tasksClassAntiga = $this->repository->findByClass($idClassAntiga);
        if(count($tasksClassAntiga) == 0){
            $classRemove = $classificacaoRepository->find($idClassAntiga);
            $entityManager->remove($classRemove);
            $entityManager->flush();
            $tasksClassAntiga = true;
        }
        
        return $this->redirectToRoute('projeto', ['slug' => $projeto->getSlug()]);

    }

    public function editTaskForm(int $idTask, int $idProjeto): Response{
        $task = $this->repository->find($idTask);
        $form = $this->createForm(TaskTypeEdit::class, $task);
        return $this->render('projeto/_editTask.html.twig', ["form" => $form->createView(), "idTask" => $idTask, "idProjeto" => $idProjeto]);
    }

}