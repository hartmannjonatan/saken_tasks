<?php

namespace App\Controller;

use App\Entity\Projeto;
use App\Form\Type\ProjetoType;
use App\Repository\ProjetoRepository;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use ProxyManager\ProxyGenerator\ValueHolder\MethodGenerator\Constructor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

            $entityManager->persist($projeto);

            $projeto = $form->getData();

            try {
                $entityManager->persist($projeto);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'Ocorreu um erro, tente novamente mais tarde!'
                );
                
                return $this->redirectToRoute('home');
            }
            

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
    public function read(ProjetoRepository $projetoRepository, string $slug): Response
    {
        $projeto = $projetoRepository->findOneBy(["slug" => $slug]);

        if(!$projeto){
            $this->addFlash(
                'error',
                'Esse projeto não existe!'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('projeto/projeto.html.twig', [
            'projeto' => $projeto,
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

}