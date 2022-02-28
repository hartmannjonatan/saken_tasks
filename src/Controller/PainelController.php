<?php

namespace App\Controller;

use App\Entity\Painel;
use App\Repository\PainelRepository;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PainelController extends AbstractController
{
    protected $repository;

    public function __construct(PainelRepository $painelRepository)
    {
        $this->repository = $painelRepository;
    }

    #[Route('/painel/{id}', name: 'readPainel')]
    public function readPainel(PainelRepository $painelRepository, int $id): Response
    {
        $painel = $painelRepository->find($id);

        return $this->render('painel/painel.html.twig', [
            'painel' => $painel
        ]);
    }

    public function readListMenu(int $max = 5): Response
    {
        $paineis = $this->repository->findAllDesc();

        return $this->render('painel/_listaPaineisMenu.html.twig', [
            'paineis' => $paineis,
        ]);
    }

    public function readListNav(): Response
    {
        $paineis = $this->repository->findAllDesc();

        return $this->render('painel/_listaPaineisNav.html.twig', [
            'paineis' => $paineis,
        ]);
    }

}