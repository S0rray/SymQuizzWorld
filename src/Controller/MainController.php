<?php

namespace App\Controller;

use App\Entity\Themes;
use App\Entity\Difficulties;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(
        Request $request, 
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        ): Response
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

        
        $paginate = $paginator->paginate(
            $completedThemes,
            $request->query->getInt('page', 1),
            8
        ); 

        return $this->render('main/index.html.twig', [
            'completedThemes' => $paginate,
            'difficulties' => $difficulties,
        ]);
    }
}
