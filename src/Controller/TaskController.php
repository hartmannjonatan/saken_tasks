<?php

namespace App\Controller;

use App\Entity\Classificacao;
use App\Entity\Task;
use App\Form\Type\TaskType;
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

}