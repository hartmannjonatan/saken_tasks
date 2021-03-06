<?php

namespace App\Controller;

use App\Entity\Projeto;
use App\Entity\Painel;
use App\Form\Type\ProjetoTypeEdit;
use App\Form\Type\ProjetoType;
use App\Form\Type\imgChooseType;
use App\Repository\UserRepository;
use App\Repository\ProjetoRepository;
use App\Repository\TaskRepository;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Length;

class ProjetoController extends AbstractController
{
    protected $repository;

    public function __construct(ProjetoRepository $projetoRepository)
    {
        $this->repository = $projetoRepository;
    }

    #[Route('projeto/new', name: 'newProjeto')]
    public function create(Request $request, ManagerRegistry $doctrine): Response {

        $entityManager = $doctrine->getManager();

        $projeto = new Projeto();

        $form = $this->createForm(ProjetoType::class, $projeto);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $nome = $form->get('nome')->getData();
            /** @var string $slug */
            $slug = str_replace([' ', '/'], ['_', '%'], $nome);

            $projeto->setSlug($slug);
            $projeto->setCreatedAt();
            $projeto->setUpdatedAt();

            $path = "/img/cover-default.png";
            $projeto->setUrlImgCover($path);

            $entityManager->persist($projeto);

            $projeto = $form->getData();

            try {
                $entityManager->persist($projeto);
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'Ocorreu um erro, tente novamente mais tarde!'
                );
                
                return $this->redirectToRoute('projetos');
            }

            $painel = new Painel();
            $painel->setTitle($projeto->getNome());
            $painel->setCodProjeto($projeto);
            
            $entityManager->persist($painel);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'O projeto foi cadastrado com sucesso!'
            );
            
            return $this->redirectToRoute('projeto', ['slug' => $projeto->getSlug()]);
        }

        return $this->renderForm('projeto/newProjeto.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('projeto/{slug}', name: 'projeto')]
    public function read(UserRepository $userRepository, Security $security, ProjetoRepository $projetoRepository, string $slug, TaskRepository $taskRepository, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $projeto = $projetoRepository->findSlugAndJoin($slug);
        $projeto = $projeto[0];

        $tasks = $taskRepository->findAllByProjeto($projeto["id"]);

        $user = $security->getUser();
        $user = $userRepository->find($user->id);
        $user->setRecentProject($projetoRepository->find($projeto["id"]));
        $entityManager->persist($user);
        $entityManager->flush();

        if(empty($projeto)){
            $this->addFlash(
                'error',
                'Esse projeto n??o existe!'
            );

            return $this->redirectToRoute('projetos');
        }

        return $this->render('projeto/projeto.html.twig', [
            'projeto' => $projeto,
            'tasks' => $tasks,
        ]);
    }

    #[Route('/projetos', name: 'projetos')]
    public function listProjetos(ProjetoRepository $projetoRepository): Response
    {
        $projetos = $projetoRepository->findAllAndJoin();

        return $this->render('projeto/listaProjetos.html.twig', [
            'projetos' => $projetos,
        ]);
    }

    public function readListMenu(int $max = 5): Response
    {
        $projetos = $this->repository->findAllDesc();

        return $this->render('projeto/_listaProjetosMenu.html.twig', [
            'projetos' => $projetos,
        ]);
    }

    public function readListNav(): Response
    {
        $projetos = $this->repository->findAllDesc();

        return $this->render('projeto/_listaProjetosNav.html.twig', [
            'projetos' => $projetos,
        ]);
    }

    public function chooseImgCoverForm(): Response{
        
        $form = $this->createForm(imgChooseType::class);
        return $this->render('projeto/_chooseImgForm.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @Route("/chooseImgCover", methods="POST")
     */
    public function chooseImgAction(Request $request, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();

        $form = $this->createForm(imgChooseType::class);

        $form->handleRequest($request);
        $projeto = $this->repository->find($form->get("id")->getData());

        if($form->isValid()){
            $arquivo = $form->get("img")->getData();
            $typeFile = $arquivo->getClientOriginalName();
            $typeFile = explode(".", $typeFile);
            $aux = count($typeFile) - 1;
            $typeFile = $typeFile[$aux];
            $nomeFile = $projeto->getId().".".$typeFile;

            $path = "/img/upload/".$nomeFile;
            if(file_exists( $path )){
                unlink($path);
            }
            $projeto->setUrlImgCover($path);

            $entityManager->persist($projeto);
            $entityManager->flush();

            $arquivo->move($this->getParameter("kernel.project_dir")."/public/img/upload", $nomeFile);
        }

        return $this->redirectToRoute("projeto", ['slug' => $projeto->getSlug()]);
    }

    #[Route('projeto/removeImgCover/{id}', name: 'removeImgCover')]
    public function removeImgCover(int $id, Request $request, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();

        $projeto = $this->repository->find($id);

        $path = "/img/cover-default.png";
        $projeto->setUrlImgCover($path);

        $entityManager->persist($projeto);
        $entityManager->flush();

        return $this->redirectToRoute("projeto", ['slug' => $projeto->getSlug()]);
    }

    public function configProjetoForm(int $id): Response{
        
        $projeto = $this->repository->find($id);
        $form = $this->createForm(ProjetoTypeEdit::class, $projeto);
        return $this->render('projeto/_configProjetoForm.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @Route("/update", methods="POST")
     */
    public function update(Request $request, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();

        $form = $this->createForm(ProjetoTypeEdit::class);

        $form->handleRequest($request);
        $projeto = $this->repository->find($form->get("id")->getData());

        if($form->isValid()){
            $nome = $form->get('nome')->getData();
            /** @var string $slug */
            $slug = str_replace([' ', '/'], ['_', '%'], $nome);

            $projeto->setSlug($slug);
            $projeto->setUpdatedAt();
            $projeto->setNome($form->get('nome')->getData());
            $projeto->setCliente($form->get('cliente')->getData());
            $projeto->setDescricao($form->get('descricao')->getData());
            $projeto->setCoordenador($form->get('coordenador')->getData());

            try {
                $entityManager->persist($projeto);
                $entityManager->flush();
                $this->addFlash(
                    'success',
                    'O projeto foi alterado com sucesso!'
                );
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'Ocorreu um erro, tente novamente mais tarde!'
                );
            }
        
        }

        return $this->redirectToRoute("projeto", ['slug' => $slug]);
    }

    #[Route('/projeto/delete/{id}', name: 'deleteProjeto')]
    public function delete(int $id, Request $request, ManagerRegistry $doctrine, ProjetoRepository $projetoRepository): Response {
        $entityManager = $doctrine->getManager();
        $projeto = $projetoRepository
            ->find($id);

        if(!$projeto){
            $this->addFlash(
                'error',
                'Esse projeto n??o existe. :('
            );
            
            return $this->redirectToRoute('projetos');
        }

            $tasks = $projeto->getCodTasks();
            if(count($tasks) > 0){
                foreach($tasks as $task){
                    $entityManager->remove($task);
                }
                $entityManager->flush();
            }


            $this->addFlash(
                'success',
                'O projeto "'.$projeto->getNome().'" foi deletado com sucesso!'
            );

            $entityManager->remove($projeto);
            $entityManager->flush();
            
            return $this->redirectToRoute('projetos');
    }
}