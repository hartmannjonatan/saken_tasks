<?php

namespace App\Controller;

use App\Entity\Funcionario;
use App\Entity\User;
use App\Form\Type\FuncionarioType;
use App\Form\Type\UserType;
use App\Repository\FuncionarioRepository;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FuncionarioController extends AbstractController
{
    #[Route('admin/new/User', name: 'newUser')]
    public function newUser(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher): Response {

        $entityManager = $doctrine->getManager();

        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            $user->setRoles();

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('newFunc', ['userId' => $user->getId()]);
        }

        return $this->renderForm('admin/newUser.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('admin/new/funcionario/{userId}', name: 'newFunc')]
    public function create(Request $request, ManagerRegistry $doctrine, int $userId, UserRepository $userRepository): Response {

        $entityManager = $doctrine->getManager();

        $funcionario = new Funcionario();

        $form = $this->createForm(FuncionarioType::class, $funcionario);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $cpf = str_replace(['.', '-'], ['', ''], $form->get('cpf')->getData());
            $funcionario->setCpf($cpf);
            $funcionario->setCodUser($userRepository->find($userId));

            $entityManager->persist($funcionario);

            $funcionario = $form->getData();

            try {
                $entityManager->persist($funcionario);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'Ocorreu um erro, tente novamente mais tarde!'
                );
                
                return $this->redirectToRoute('newFunc');
            }
            

            $this->addFlash(
                'success',
                'O funcionário "'.$funcionario->getNome().'" foi cadastrado com sucesso!'
            );
            
            return $this->redirectToRoute('func');
        }

        return $this->renderForm('admin/newFunc.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/admin/funcionarios', name: 'func')]
    public function func(FuncionarioRepository $funcionarioRepository): Response
    {
        $funcionarios = $funcionarioRepository->findAllAndJoin();

        return $this->render('admin/func.html.twig', [
            'funcionarios' => $funcionarios,
        ]);
    }
}