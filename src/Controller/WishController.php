<?php

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/wish', name: 'app_wish_')]
final class WishController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(WishRepository $whishRepository): Response
    {
        $wishes = $whishRepository->findBy(['isPublished' => true], ['dateCreated' => 'DESC']);
        return $this->render('wish/list.html.twig', [
            "wishes" => $wishes
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
}
