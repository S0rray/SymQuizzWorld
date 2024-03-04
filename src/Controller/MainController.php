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
        // Vérifier si l'utilisateur est connecté ou non
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
            return $this->redirectToRoute('app_login');
        }
        // return $this->render('main/index.html.twig', [
        //     'themes' => $themesRepository->getName(),
        //     'difficulties' => $difficultiesRepository->getName()
        // ]);
        return $this->render('main/index.html.twig');
    }
}
