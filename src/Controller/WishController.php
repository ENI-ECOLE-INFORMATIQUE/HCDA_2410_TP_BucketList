<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/wish', name: 'app_wish_')]
final class WishController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(): Response
    {
        return $this->render('wish/list.html.twig', [
        ]);
    }

    #[Route('/wishes/{id}', name: 'detail', methods: ['GET'],requirements: ['id'=>'\d+'])]
    public function detail(int $id): Response
    {
        return $this->render('wish/detail.html.twig', [
        ]);
    }
}
