<?php

namespace App\Controller;

use App\Entity\Projeto;
use App\Form\Type\ProjetoTypeEdit;
use App\Form\Type\ProjetoType;
use App\Form\Type\imgChooseType;
use App\Repository\ProjetoRepository;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Length;

class CategoriaController extends AbstractController
{

}