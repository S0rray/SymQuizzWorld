<?php

namespace App\Controller;

use App\Entity\Difficulties;
use App\Entity\Themes;
use App\Repository\DifficultiesRepository;
use App\Repository\ThemesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(EntityManagerInterface $em): Response
    {
        // Vérifier si l'utilisateur est connecté ou non
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
            return $this->redirectToRoute('app_login');
        }

        // Récupération des thèmes en état complet
        $completedThemes = $em->getRepository(Themes::class)->findBy(['statut' => 'Complet']);

        // Récupération des difficultés
        $difficulties = $em->getRepository(Difficulties::class)->findBy([], ['id' => 'ASC']);

        return $this->render('main/index.html.twig', [
            'completedThemes' => $completedThemes,
            'difficulties' => $difficulties,
        ]);
    }
}
