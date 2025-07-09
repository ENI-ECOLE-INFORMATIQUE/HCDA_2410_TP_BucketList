<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/wish', name: 'app_wish_')]
final class WishController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(WishRepository $whishRepository): Response
    {
        //$wishes = $whishRepository->findBy(['isPublished' => true], ['dateCreated' => 'DESC']);
        $wishes = $whishRepository->findPublishedWishesWithCategories();
        return $this->render('wish/list.html.twig', [
            "wishes" => $wishes
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET','POST'])]
    public function create(Request $request, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $wish = new Wish();
        //On associe le formulaire à notre objet ici wish
        $wishForm = $this->createForm(WishType::class, $wish);
        //On récupère les données du form et on les injecte dans l'objet wish
        $wishForm->handleRequest($request);
        //Si le formulaire est soumis et qu'il est valide
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            $wish->setIsPublished(true);
            //traitement de l'image
            $imageFile = $wishForm->get('image')->getData();
            if ($imageFile) {
                $wish->setFilename($fileUploader->upload($imageFile));
            }
            //Sauvegarde en BD
            $em->persist($wish);
            $em->flush();
            //Affiche le message
            $this->addFlash('success', 'Wish successfully created!');
            //redirige vers la page de détail.
            return $this->redirectToRoute('app_wish_detail', ['id' => $wish->getId()]);
        }
        return $this->render('wish/create.html.twig', [
            "wishForm" => $wishForm
        ]);
    }

    #[Route('/{id}/update', name: 'update', methods: ['GET','POST'], requirements: ['id'=>'\d+'])]
    public function update(int $id, WishRepository $wishRepository, Request $request,
                           EntityManagerInterface $em,FileUploader $fileUploader): Response
    {
        $wish = $wishRepository->find($id);
        if(!$wish){
            throw $this->createNotFoundException('Wish not found, sorry !');
        }
        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            $wish->setDateUpdated(new \DateTimeImmutable());
            //traitement de l'image
            $imageFile = $wishForm->get('image')->getData();
            if (($wishForm->has('deleteImage') && $wishForm['deleteImage']->getData()) || $imageFile) {
                //Suppression de l'ancienne image
                //Si on a coché l'option dans le formulaire ou si on a changé l'image
                $fileUploader->delete($wish->getFilename(),
                    $this->getParameter('app.images_wish_directory'));
                if($imageFile){
                    $wish->setFilename($fileUploader->upload($imageFile));
                }else{
                    $wish->setFilename(null);
                }
            }
            $em->flush();
            $this->addFlash('success', 'Wish successfully updated!');
            return $this->redirectToRoute('app_wish_detail', ['id' => $wish->getId()]);
        }
        return $this->render('wish/create.html.twig', [
            "wishForm" => $wishForm
        ]);
    }

    #[Route('/wishes/{id}', name: 'detail', methods: ['GET'],requirements: ['id'=>'\d+'])]
    public function detail(int $id,WishRepository $wishRepository): Response
    {
        //récupère le wish en fonction de l'id présent dans l'URL
        $wish = $wishRepository->find($id);
        //S'il n'existe pas en bdd, on déclenche une erreur 404
        if(!$wish){
            throw $this->createNotFoundException('Wish not found, Sorry !');
        }
        return $this->render('wish/detail.html.twig', ["wish" => $wish
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', requirements: ['id'=>'\d+'], methods: ['GET'])]
    public function delete(Wish $wish,Request $request, EntityManagerInterface $em): Response
    {
        if($this->isCsrfTokenValid('delete-'.$wish->getId(), $request->get('token'))) {
            try {
                $em->remove($wish);
                $em->flush();
                $this->addFlash('success', 'The wish has been deleted.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'The wish cannot be deleted.');
            }
        }else{
            $this->addFlash('danger', 'The wish cannot be deleted: Pb Token');
        }
        return $this->redirectToRoute('app_wish_list');
    }
}
