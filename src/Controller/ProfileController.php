<?php

namespace App\Controller;

use App\Entity\Themes;
use App\Form\ThemesFormType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/profil', name: 'profil_')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
    #[Route('/mes-scores', name: 'scores')]
    public function scores(): Response
    {
        return $this->render('profile/scores.html.twig', [
            'controller_name' => 'ProfileScoresController',
        ]);
    }
    #[Route('/mes-themes', name: 'themes')]
    public function themes(Request $request, EntityManager $em, SluggerInterface $slugger): Response
    {
        //Création d'un nouveau thème
        $theme = new Themes();

        //Création du formulaire
        $themeForm = $this->createForm(ThemesFormType::class, $theme);

        //Traitement de la requête du formulaire
        $themeForm->handleRequest($request);

        //Vérification si le formulaire est soumis ET valide
        if($themeForm->isSubmitted() && $themeForm->isValid())
        {
            //Génération du slug
            $slug = $slugger->slug($theme->getName());
            $theme->setSlug($slug);

            //Stockage du formulaire
            $em->persist($theme);
            $em->flush();

            $this->addFlash('success','Theme créer avec succès');

            //Redirection
            return $this->redirectToRoute('ajout_theme');
        }

        return $this->render('profile/themes.html.twig', [
            'themeForm' => $themeForm->createView()
        ]);
    }
    #[Route('/mes-themes/ajout', name: 'ajout_theme')]
    public function addTheme(): Response
    {
        return $this->render('profile/ajout_theme.html.twig', [
            'controller_name' => 'ProfileRemoveController',
        ]);
    }
    #[Route('/supprimer-mon-compte', name: 'remove')]
    public function remove(): Response
    {
        return $this->render('profile/remove.html.twig', [
            'controller_name' => 'ProfileRemoveController',
        ]);
    }
}
