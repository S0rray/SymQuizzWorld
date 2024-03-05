<?php

namespace App\Controller;

use App\Entity\Proposals;
use App\Entity\Questions;
use App\Entity\Themes;
use App\Form\ProposalsFormType;
use App\Form\QuestionsFormType;
use App\Form\ThemesFormType;
use App\Service\PictureService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
        // Vérifier si l'utilisateur est connecté ou non
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
            return $this->redirectToRoute('app_login');
        }


        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
    #[Route('/mes-scores', name: 'scores')]
    public function scores(): Response
    {
        // Vérifier si l'utilisateur est connecté ou non
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
            return $this->redirectToRoute('app_login');
        }


        return $this->render('profile/scores.html.twig', [
            'controller_name' => 'ProfileScoresController',
        ]);
    }
    #[Route('/mes-themes', name: 'themes')]
    public function addThemes(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, PictureService $pictureService): Response
    {
        // Vérifier si l'utilisateur est connecté ou non
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
            return $this->redirectToRoute('app_login');
        }

        // Récupération de l'utilisateur
        $user = $this->getUser();

        //Création d'un nouveau thème
        $theme = new Themes();

        //Création du formulaire
        $themeForm = $this->createForm(ThemesFormType::class, $theme);

        //Traitement de la requête du formulaire
        $themeForm->handleRequest($request);

        //Vérification si le formulaire est soumis ET valide
        if($themeForm->isSubmitted() && $themeForm->isValid())
        {
            //Récupération de l'image
            $picture = $themeForm->get('picture')->getData();

            //On définit le dossier de destination
            $folder = 'themes';
            
            //Appel du service d'ajout
            $fichier = $pictureService->add($picture, $folder, 862, 398);
            
            $theme->setPicture($fichier);

            $theme->setCreatorId($user);

            //Génération du slug
            $slug = $slugger->slug($theme->getName());
            $theme->setSlug($slug);

            //Définition du statut
            $theme->setStatut('En cours');

            //Stockage du formulaire
            $em->persist($theme);
            $em->flush();

            $this->addFlash('success','Theme créer avec succès');

            //Redirection
            return $this->redirectToRoute('profil_ajout_question_debutant');
        }

        return $this->render('profile/themes.html.twig', [
            'themeForm' => $themeForm->createView()
        ]);
    }
    #[Route('/mes-themes/ajout/{slug}', name: 'ajout_question_debutant')]
    public function addNovice(Request $request, EntityManagerInterface $emi, string $slug): Response
    {
        // Vérifier si l'utilisateur est connecté ou non
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
            return $this->redirectToRoute('app_login');
        }

        // Récupération du thème via le slug
        $theme = $emi->getRepository(Themes::class)->findOneBy(['slug' => $slug]);

        // Gestion de l'erreur si le theme n'est pas trouvé
        if(!$theme)
        {
            throw $this->createNotFoundException('Le thème correspondant a "' . $slug . '" n\'existe pas.');
        }

        //Création d'une nouvelle question
        $question = new Questions();

        // Définir le thème lié à la question
        $question->setIdTheme($theme);
        dd($question);
        //Création du formulaire nouvelle question
        $questionForm = $this->createForm(QuestionsFormType::class, $question);

        //Création d'une nouvelle proposition
        $proposal = new Proposals();

        //Création du formulaire nouvelle proposition
        $proposalForm = $this->createForm(ProposalsFormType::class, $proposal);

        //Création du formulaire imbriqué
        $addQuestionForm = $this->createFormBuilder()
            ->add('question', QuestionsFormType::class)
            ->add('proposal', ProposalsFormType::class)
            ->getForm();
        
        //Traitement de la requête du formulaire
        $addQuestionForm->handleRequest($request);


        return $this->render('profile/ajout_question_debutant.html.twig', [
            'controller_name' => 'ProfileRemoveController',
        ]);
    }

    // Partie de suppression du compte
    #[Route('/supprimer-mon-compte', name: 'remove')]
    public function remove(): Response
    {
        // Vérifier si l'utilisateur est connecté ou non
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
            return $this->redirectToRoute('app_login');
        }

        
        return $this->render('profile/remove.html.twig', [
            'controller_name' => 'ProfileRemoveController',
        ]);
    }
    #[Route('/supprimer-mon-compte/confirmation', name: 'confirm_remove')]
    public function confirmRemove(): Response
    {
        // Vérifier si l'utilisateur est connecté ou non
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
            return $this->redirectToRoute('app_login');
        }

        
        return $this->render('profile/remove.html.twig', [
            'controller_name' => 'ProfileRemoveController',
        ]);
    }
}
