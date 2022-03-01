<?php

namespace App\Controller;

use App\Repository\FuncionarioRepository;
use App\Repository\GerenteRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(UserRepository $userRepository, GerenteRepository $gerenteRepository, Security $security, FuncionarioRepository $funcionarioRepository, TaskRepository $taskRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $security->getUser();
        $recents = $userRepository->findRecents($user->id);
        if(!$recents){
            $recents = null;
        }else $recents = $recents[0];
        $roles = $user->getRoles();
        if((in_array("ROLE_ADMIN", $roles) == true) || (in_array("ROLE_SUPER_ADMIN", $roles) == true)){
            $gerente = $gerenteRepository->findIdAndJoin($user->id);
            $gerente = $gerente[0];
            return $this->render('home/index.html.twig', [
                'user' => $gerente,
                'recents' => $recents,
            ]);
        } else{
            $funcionario = $funcionarioRepository->findIdAndJoin($user->id);
            $funcionario = $funcionario[0];

            $tasks = $taskRepository->findAllByFuncLimiter($funcionario["id"]);
            $projetoId = $tasks[0];
            $projetoId = $projetoId["projeto"];

            $has = false;

            foreach($tasks as $task){
                if($task["conclued_at"] == null){
                    $has = true;
                    break;
                }
            }

            return $this->render('home/index.html.twig', [
                'user' => $funcionario,
                'tasks' => $tasks,
                'projetoId' => $projetoId,
                'has' => $has,
                'recents' => $recents,
            ]);
        }
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
        return $this->render('home/userPage.html.twig', [
            'user' => $funcionario,
            'tasks' => $tasks,
            'projetoId' => $projetoId,
            'has' => $has,
        ]);
    }

}
