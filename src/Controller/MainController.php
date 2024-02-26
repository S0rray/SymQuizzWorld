<?php

namespace App\Controller;

use App\Repository\DifficultiesRepository;
use App\Repository\ThemesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(ThemesRepository $themesRepository, DifficultiesRepository $difficultiesRepository): Response
    {
        return $this->render('main/index.html.twig', [
            'themes' => $themesRepository->getName(),
            'difficulties' => $difficultiesRepository->getName()
        ]);
    }
}
