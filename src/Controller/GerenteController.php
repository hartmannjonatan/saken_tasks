<?php

namespace App\Controller;

use App\Entity\Gerente;
use App\Entity\User;
use App\Form\Type\GerenteType;
use App\Form\Type\GerenteTypeEdit;
use App\Form\Type\UserTypeGerente;
use App\Form\Type\UserTypeEditGerente;
use App\Form\Type\DeleteConfirmType;
use App\Repository\GerenteRepository;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GerenteController extends AbstractController
{
    #[Route('admin/new/UserGerente', name: 'newUserGerente')]
    public function newUser(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher): Response {

        $entityManager = $doctrine->getManager();

        $user = new User();

        $form = $this->createForm(UserTypeGerente::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            
            $superadmin = $form->get('superadmin')->getData();

            if($superadmin == true){
                $user->setRoles(["ROLE_SUPER_ADMIN"]);
            } else $user->setRoles(["ROLE_ADMIN"]);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('newGerente', ['userId' => $user->getId()]);
        }

        return $this->renderForm('admin/newUser.html.twig', [
            'form' => $form,
            'gerente' => true,
        ]);
    }

    #[Route('admin/new/gerente/{userId}', name: 'newGerente')]
    public function create(Request $request, ManagerRegistry $doctrine, int $userId, UserRepository $userRepository): Response {

        $entityManager = $doctrine->getManager();

        $gerente = new Gerente();

        $form = $this->createForm(GerenteType::class, $gerente);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $gerente->setCodUser($userRepository->find($userId));

            $entityManager->persist($gerente);

            $gerente = $form->getData();

            try {
                $entityManager->persist($gerente);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'Ocorreu um erro, tente novamente mais tarde!'
                );
                
                return $this->redirectToRoute('gerente');
            }
            

            $this->addFlash(
                'success',
                'O gerente "'.$gerente->getNome().'" foi cadastrado com sucesso!'
            );
            
            return $this->redirectToRoute('gerente');
        }

        return $this->renderForm('admin/newGerente.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/admin/gerentes', name: 'gerente')]
    public function read(GerenteRepository $gerenteRepository): Response
    {
        $gerente = $gerenteRepository->findAllAndJoin();

        return $this->render('admin/gerente.html.twig', [
            'gerentes' => $gerente,
        ]);
    }

    #[Route('/admin/gerentes/update/{id}', name: 'updateGerente')]
    public function update(int $id, Request $request, ManagerRegistry $doctrine, GerenteRepository $gerenteRepository, UserPasswordHasherInterface $userPasswordHasher): Response {
        $entityManager = $doctrine->getManager();

        $gerente = $gerenteRepository->find($id);

        if(!$gerente){
            $this->addFlash(
                'error',
                'Esse Gerente não existe.'
            );
            
            return $this->redirectToRoute('gerente');
        }

        $form = $this->createForm(GerenteTypeEdit::class, $gerente);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var \App\Entity\User $userConfirmated */
            $userConfirmated = $this->getUser();

            if(!$userPasswordHasher->isPasswordValid($userConfirmated, $form->get('plainPassword')->getData())){
                $this->addFlash(
                    'error',
                    'Sua senha está incorreta! Por motivos de segurança não alteramos o(a) gerente '.$form->get('nome')->getData()."!"
                );
                return $this->redirectToRoute('gerente');
            } else{
                
                $gerente = $form->getData();

                try {
                    $entityManager->persist($gerente);
                    $entityManager->flush();
                } catch (\Exception $e) {
                    $this->addFlash(
                        'error',
                        'Ocorreu um erro, tente novamente mais tarde!'
                    );

                    return $this->redirectToRoute('gerente');
                }
                

                $this->addFlash(
                    'success',
                    'O gerente "'.$gerente->getNome().'" foi editado com sucesso!'
                );
                
                return $this->redirectToRoute('gerente');
            }
        }

        return $this->renderForm('admin/newGerente.html.twig', [
            'form' => $form,
            'edit' => true,
        ]);
    }

    #[Route('/admin/userGerente/update/{id}', name: 'updateLoginGerente')]
    public function updateLogin(int $id, Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher): Response {

        $entityManager = $doctrine->getManager();
        $users = $entityManager->getRepository(User::class)->find($id);

        if(!$users){
            $this->addFlash(
                'error',
                'Esse Usuário não existe.'
            );
            
            return $this->redirectToRoute('gerente');
        }

        $form = $this->createForm(UserTypeEditGerente::class, $users);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var \App\Entity\User $userConfirmated */
            $userConfirmated = $this->getUser();

            if(!$userPasswordHasher->isPasswordValid($userConfirmated, $form->get('passwordAdmin')->getData())){
                $this->addFlash(
                    'error',
                    'Sua senha está incorreta! Por motivos de segurança não alteramos o login "'.$form->get('username')->getData().'"!'
                );
                return $this->redirectToRoute('gerente');
            } else{
                $user = $form->getData();
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        )
                    );
                
                $superadmin = $form->get('superadmin')->getData();

                if($superadmin == true){
                    $user->setRoles(["ROLE_SUPER_ADMIN"]);
                } else $user->setRoles(["ROLE_ADMIN"]);

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

                    return $this->redirectToRoute('gerente');
                }
                

                $this->addFlash(
                    'success',
                    'O Login "'.$user->getUsername().'" foi editado com sucesso!'
                );

                return $this->redirectToRoute('gerente');
            }
        }

        return $this->renderForm('admin/newUser.html.twig', [
            'form' => $form,
            'edit' => true,
            'gerente' => true
        ]);
    }

    #[Route('/admin/gerente/delete/{id}', name: 'deleteGerente')]
    public function delete(int $id, Request $request, ManagerRegistry $doctrine, GerenteRepository $gerenteRepository, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response {
        $entityManager = $doctrine->getManager();
        $gerente = $gerenteRepository
            ->find($id);
        $user = $userRepository->find($gerente->getCodUser());

        if(!$gerente){
            $this->addFlash(
                'error',
                'Esse gerente não existe. :('
            );
            
            return $this->redirectToRoute('gerente');
        }

        $form = $this->createForm(DeleteConfirmType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var \App\Entity\User $userConfirmated */
            $userConfirmated = $this->getUser();

            if(!$userPasswordHasher->isPasswordValid($userConfirmated, $form->get('passwordAdmin')->getData())){
                $this->addFlash(
                    'error',
                    'Sua senha está incorreta! Por motivos de segurança não deletamos o(a) gerente "'.$gerente->getNome().'"!'
                );
                return $this->redirectToRoute('gerente');
            } else{
                $this->addFlash(
                    'success',
                    'O gerente "'.$gerente->getNome().'" foi deletado com sucesso! Juntamente com seus dados de login.'
                );

                $entityManager->remove($gerente);
                $entityManager->flush();

                $entityManager->remove($user);
                $entityManager->flush();
                
                
                return $this->redirectToRoute('gerente');
            }
        }

        return $this->renderForm('admin/confirmPassword.html.twig', [
            'form' => $form,
            'gerente' => true,
        ]);

    }
}