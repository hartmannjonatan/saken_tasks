<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/funcionarios', name: 'func')]
    public function func(): Response
    {
        return $this->render('admin/func.html.twig');
    }

    #[Route('/admin/gerentes', name: 'ger')]
    public function ger(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        return $this->render('admin/ger.html.twig');
    }
}