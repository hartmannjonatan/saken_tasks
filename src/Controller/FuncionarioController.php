<?php

namespace App\Controller;

use App\Entity\Funcionario;
use App\Entity\User;
use App\Form\Type\FuncionarioType;
use App\Form\Type\FuncionarioTypeEdit;
use App\Form\Type\UserType;
use App\Form\Type\UserTypeEdit;
use App\Form\Type\DeleteConfirmType;
use App\Form\Type\ConfirmType;
use App\Repository\FuncionarioRepository;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

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
    public function read(FuncionarioRepository $funcionarioRepository): Response
    {
        $funcionarios = $funcionarioRepository->findAllAndJoin();

        return $this->render('admin/func.html.twig', [
            'funcionarios' => $funcionarios,
        ]);
    }

    #[Route('/admin/funcionarios/update/{id}', name: 'updateFunc')]
    public function update(int $id, Request $request, ManagerRegistry $doctrine, FuncionarioRepository $funcionarioRepository, UserPasswordHasherInterface $userPasswordHasher): Response {
        $entityManager = $doctrine->getManager();

        $funcionario = $funcionarioRepository->find($id);

        if(!$funcionario){
            $this->addFlash(
                'error',
                'Esse Funcionário não existe.'
            );
            
            return $this->redirectToRoute('func');
        }

        $form = $this->createForm(FuncionarioTypeEdit::class, $funcionario);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var \App\Entity\User $userConfirmated */
            $userConfirmated = $this->getUser();

            if(!$userPasswordHasher->isPasswordValid($userConfirmated, $form->get('plainPassword')->getData())){
                $this->addFlash(
                    'error',
                    'Sua senha está incorreta! Por motivos de segurança não alteramos o(a) funcionário(a) '.$form->get('nome')->getData()."!"
                );
                return $this->redirectToRoute('func');
            } else{
                $cpf = str_replace(['.', '-'], ['', ''], $form->get('cpf')->getData());
                $funcionario->setCpf($cpf);

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

                    return $this->redirectToRoute('func');
                }
                

                $this->addFlash(
                    'success',
                    'O funcionário "'.$funcionario->getNome().'" foi editado com sucesso!'
                );
                
                return $this->redirectToRoute('func');
            }
        }

        return $this->renderForm('admin/newFunc.html.twig', [
            'form' => $form,
            'edit' => true,
        ]);
    }

    #[Route('/admin/user/update/{id}', name: 'updateLogin')]
    public function updateLogin(int $id, Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher): Response {

        $entityManager = $doctrine->getManager();
        $users = $entityManager->getRepository(User::class)->find($id);

        if(!$users){
            $this->addFlash(
                'error',
                'Esse Usuário não existe.'
            );
            
            return $this->redirectToRoute('func');
        }

        $form = $this->createForm(UserTypeEdit::class, $users);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var \App\Entity\User $userConfirmated */
            $userConfirmated = $this->getUser();

            if(!$userPasswordHasher->isPasswordValid($userConfirmated, $form->get('passwordAdmin')->getData())){
                $this->addFlash(
                    'error',
                    'Sua senha está incorreta! Por motivos de segurança não alteramos o login "'.$form->get('username')->getData().'"!'
                );
                return $this->redirectToRoute('func');
            } else{
                $user = $form->getData();
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        )
                    );

                $entityManager->persist($user);
                $entityManager->flush();

                try {
                    $entityManager->persist($user);
                    $entityManager->flush();
                } catch (\Exception $e) {
                    $this->addFlash(
                        'error',
                        'Ocorreu um erro... Tente novamente mais tarde!'
                    );

                    return $this->redirectToRoute('func');
                }
                

                $this->addFlash(
                    'success',
                    'O Login "'.$user->getUsername().'" foi editado com sucesso!'
                );

                return $this->redirectToRoute('func');
            }
        }

        return $this->renderForm('admin/newUser.html.twig', [
            'form' => $form,
            'edit' => true,
        ]);
    }

    #[Route('/admin/funcionario/delete/{id}', name: 'deleteFunc')]
    public function delete(int $id, Request $request, ManagerRegistry $doctrine, FuncionarioRepository $funcionarioRepository, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response {
        $entityManager = $doctrine->getManager();
        $funcionario = $funcionarioRepository
            ->find($id);
        $user = $userRepository->find($funcionario->getCodUser());

        if(!$funcionario){
            $this->addFlash(
                'error',
                'Esse funcionário não existe. :('
            );
            
            return $this->redirectToRoute('func');
        }

        $form = $this->createForm(DeleteConfirmType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var \App\Entity\User $userConfirmated */
            $userConfirmated = $this->getUser();

            if(!$userPasswordHasher->isPasswordValid($userConfirmated, $form->get('passwordAdmin')->getData())){
                $this->addFlash(
                    'error',
                    'Sua senha está incorreta! Por motivos de segurança não deletamos o funcionário "'.$funcionario->getNome().'"!'
                );
                return $this->redirectToRoute('func');
            } else{
                $this->addFlash(
                    'success',
                    'O funcionário "'.$funcionario->getNome().'" foi deletado com sucesso! Juntamente com seus dados de login.'
                );

                $entityManager->remove($funcionario);
                $entityManager->flush();

                $entityManager->remove($user);
                $entityManager->flush();
                
                
                return $this->redirectToRoute('func');
            }
        }

        return $this->renderForm('admin/confirmPassword.html.twig', [
            'form' => $form,
        ]);

            
    }
}