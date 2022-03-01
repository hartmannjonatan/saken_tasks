<?php

namespace App\Controller;

use App\Entity\Painel;
use App\Entity\Note;
use App\Repository\PainelRepository;
use App\Repository\NoteRepository;
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
        $notes = $painel->getCodNotes();

        return $this->render('painel/painel.html.twig', [
            'painel' => $painel,
            'notes' => $notes,
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

    /**
     * @Route("/saveNote", name="saveNote", methods="POST")
     */
    public function saveNote(PainelRepository $painelRepository, NoteRepository $noteRepository, Request $request, ManagerRegistry $doctrine)
    {   
        $entityManager = $doctrine->getManager();

        $idPainel = $_POST['idPainel'];
        $painel = $painelRepository->find($idPainel);

        $notes = $_POST['notes'];
        $notes = json_decode($notes);

        foreach($notes as $note){
            if($note->action == true){
                if(($note->created == true) && ($note->deleting == false)){
                    $noteBd = new Note();
                    $noteBd->setId($note->id, $idPainel);
                    $noteBd->setColor($note->color);
                    $noteBd->setTitle($note->title);
                    $noteBd->setConteudo($note->content);
                    $noteBd->setPainel($painel);

                    $entityManager->persist($noteBd);
                }

                if($note->edited == true){
                    $idNote = $note->id."p".$idPainel;
                    $noteBd = $noteRepository->find($idNote);
                    $noteBd->setTitle($note->title);
                    $noteBd->setConteudo($note->content);

                    $entityManager->persist($noteBd);
                }

                if(($note->deleting == true) && ($note->created == false)){
                    $idNote = $note->id."p".$idPainel;
                    $noteBd = $noteRepository->find($idNote);

                    $entityManager->remove($noteBd);
                }
            }
        }

        $entityManager->flush();
       
        // $noteColorString = $_POST['noteColor'];
        // $idPainel = $_POST['idPainel'];
        // $titlesCreated = $_POST['createTitle'];
        // $contentCreated = $_POST['createContent'];
        // $titlesEdited = $_POST['editTitle'];
        // $contentEdited = $_POST['editContent'];
        // $painel = $painelRepository->find($idPainel);

        // if($titlesCreated != false){
        //     $titles = explode(",", $titlesCreated);
        // } else $titles = null;

        // if($contentCreated != false){
        //     $contents = explode(",", $contentCreated);
        // } else $contents = null;

        // if($titlesEdited != false){
        //     $titlesUpdate = explode(",", $titlesEdited);
        //     foreach($titlesUpdate as $key => $title){
        //         if($title != "" ){
        //             $noteUpdate = $noteRepository->find($key);
        //             $noteUpdate->setTitle($title);
        //             $entityManager->persist($noteUpdate);
        //         }
        //     }
        // }

        // if($contentEdited != false){
        //     $contentsUpdate = explode(",", $contentEdited);
        //     foreach($contentsUpdate as $key => $content){
        //         if($content != ""){
        //             $noteUpdate = $noteRepository->find($key);
        //             $noteUpdate->setConteudo($content);
        //             $entityManager->persist($noteUpdate);
        //         }
        //     }
        // }

        // if($noteColorString != false){
        //     $ids = explode(",", $noteColorString);

        //     $aux = 0;
        //     foreach($ids as $id){
        //         $aux++;
        //         if($titles != null){
        //             $key = count($titles) - $aux;
        //         }
        //         $note = new Note();
        //         $note->setColor($id);
        //         $note->setPainel($painel);
        //         if($titles == null){
        //              $note->setTitle();
        //         } else $note->setTitle($titles[$key]);
        //         if($contents == null){
        //             $note->setConteudo();
        //        } else $note->setConteudo($contents[$key]);
        //         $entityManager->persist($note);
        //     }
        // }




        // / AQUI: folia com muito tipo de id jogado, preferível criar um único objeto para cada nota no javascript
        

        // $entityManager->flush();

        return $this->redirectToRoute("readPainel", ['id' => $idPainel]);

        // return $this->render('painel/teste.html.twig', [
        //     'teste' => var_dump($idPainel),
        // ]);
    }
}  