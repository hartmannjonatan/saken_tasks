<?php

namespace App\Controller;

use App\Entity\Gerente;
use App\Entity\User;
use App\Form\Type\GerenteType;
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

class GerenteController extends AbstractController
{
    #[Route('admin/new/UserGerente', name: 'newUserGerente')]
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
            $user->setRoles(["ROLE_ADMIN"]);

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
                
                return $this->redirectToRoute('newGerente');
            }
            

            $this->addFlash(
                'success',
                'O gerente "'.$gerente->getNome().'" foi cadastrado com sucesso!'
            );
            
            return $this->redirectToRoute('home');
        }

        return $this->renderForm('admin/newGerente.html.twig', [
            'form' => $form
        ]);
    }
}