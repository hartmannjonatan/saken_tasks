<?php

namespace App\Controller;

use App\Entity\Cargo;
use App\Repository\CargoRepository;
use App\Form\Type\CargoType;
use App\Form\Type\CargoTypeEdit;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CargoController extends AbstractController
{
    #[Route('admin/new/cargo', name: 'newCargo')]
    public function create(Request $request, ManagerRegistry $doctrine): Response {

        $entityManager = $doctrine->getManager();

        $cargo = new Cargo();

        $form = $this->createForm(CargoType::class, $cargo);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $cargo = $form->getData();

            try {
                $entityManager->persist($cargo);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'Ocorreu um erro, tente novamente mais tarde!'
                );
                
                return $this->redirectToRoute('newCargo');
            }
            

            $this->addFlash(
                'success',
                'O cargo "'.$cargo->getNome().'" foi criado com sucesso!'
            );
            
            return $this->redirectToRoute('cargos');
        }

        return $this->renderForm('admin/newCargo.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/admin/cargos', name: 'cargos')]
    public function read(CargoRepository $cargoRepository): Response
    {
        $cargos = $cargoRepository->findAll();

        return $this->render('admin/cargos.html.twig', [
            'cargos' => $cargos
        ]);
    }

    #[Route('/admin/cargos/update/{id}', name: 'updateCargo')]
    public function update(int $id, Request $request, ManagerRegistry $doctrine): Response {

        $entityManager = $doctrine->getManager();
        $cargo = $entityManager->getRepository(Cargo::class)->find($id);

        if(!$cargo){
            $this->addFlash(
                'error',
                'Esse cargo não existe.'
            );
            
            return $this->redirectToRoute('cargos');
        }

        $form = $this->createForm(CargoTypeEdit::class, $cargo);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $cargo = $form->getData();
            try {
                $entityManager->persist($cargo);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'Ocorreu um erro... Tente novamente mais tarde!'
                );
                
                return $this->redirectToRoute('cargos');
            }
            

            $this->addFlash(
                'success',
                'O cargo "'.$cargo->getNome().'" foi editado com sucesso!'
            );
            
            return $this->redirectToRoute('cargos');
        }

        return $this->renderForm('admin/newCargo.html.twig', [
            'form' => $form,
            'edit' => true,
        ]);
    }

    #[Route('/admin/cargos/delete/{id}', name: 'deleteCargo')]
    public function delete(int $id, Request $request, ManagerRegistry $doctrine, CargoRepository $cargoRepository): Response {
        $entityManager = $doctrine->getManager();
        $cargo = $cargoRepository
            ->find($id);

        if(!$cargo){
            $this->addFlash(
                'error',
                'Essa cargo não existe. :('
            );
            
            return $this->redirectToRoute('cargos');
        }

            $this->addFlash(
                'success',
                'O cargo "'.$cargo->getNome().'" foi deletado com sucesso!'
            );

            $entityManager->remove($cargo);
            $entityManager->flush();
            
            
            return $this->redirectToRoute('cargos');
    }
}