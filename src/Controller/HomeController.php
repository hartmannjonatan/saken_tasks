<?php

namespace App\Controller;

use App\Repository\FuncionarioRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('home/index.html.twig');
    }

    #[Route('/userPage', name: 'userPage')]
    public function read(UserRepository $userRepository, Security $security, FuncionarioRepository $funcionarioRepository, TaskRepository $taskRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $security->getUser();

        $funcionario = $funcionarioRepository->findIdAndJoin($user->id);
        $funcionario = $funcionario[0];

        $tasks = $taskRepository->findAllByFunc($funcionario["id"]);
        $projetoId = $tasks[0];
        $projetoId = $projetoId["projeto"];

        $has = false;

        foreach($tasks as $task){
            if($task["conclued_at"] == null){
                $has = true;
                break;
            }
        }

        // return $this->render('painel/teste.html.twig', [
        //     'teste' => var_dump($tasks),
        // ]);
        return $this->render('home/userPage.html.twig', [
            'user' => $funcionario,
            'tasks' => $tasks,
            'projetoId' => $projetoId,
            'has' => $has,
        ]);
    }

}
