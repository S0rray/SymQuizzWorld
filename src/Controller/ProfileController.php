<?php

namespace App\Controller;

use App\Entity\Themes;
use App\Entity\Proposals;
use App\Entity\Questions;
use App\Entity\Difficulties;
use App\Form\ThemesFormType;
use App\Service\PictureService;
use App\Form\AddQuestionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
            return $this->redirectToRoute('profil_ajout_question', ['slug' => $theme->getSlug()]);
        }

        return $this->render('profile/themes.html.twig', [
            'themeForm' => $themeForm->createView()
        ]);
    }

    #[Route('/mes-themes/edit/{slug}', name: 'theme_edit')]
    public function editThemes(
        Request $request, 
        EntityManagerInterface $em, 
        SluggerInterface $slugger, 
        PictureService $pictureService,
        string $slug
        ): Response
    {
        // Vérifier si l'utilisateur est connecté ou non
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
            return $this->redirectToRoute('app_login');
        }

        // Récupération de l'utilisateur
        $user = $this->getUser();

        // Récupération du thème
        $theme = $em->getRepository(Themes::class)->findOneBy(['slug' => $slug]);
        $themeName = $theme->getName();

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

            //Stockage du formulaire
            $em->persist($theme);
            $em->flush();

            $this->addFlash('success','Thème mis à jour avec succès');

            //Redirection
            return $this->redirectToRoute('profil_themes');            

        }


        return $this->render('profile/edit_theme.html.twig', [
            'themeForm' => $themeForm->createView(),
            'themeName' => $themeName,
        ]);
    }

    #[Route('/mes-themes/edit/choice/{slug}', name: 'edit_choice')]
    public function choiceQuestion(
        Request $request, 
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        string $slug
        ): Response
    {
        // Vérifier si l'utilisateur est connecté ou non
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
            return $this->redirectToRoute('app_login');
        }

        // Récupération de l'utilisateur
        $user = $this->getUser();

        // Récupération du thème
        $theme = $em->getRepository(Themes::class)->findOneBy(['slug' => $slug]);
        $themeName = $theme->getName();

        $questions = $em->getRepository(Questions::class)->findBy(['theme' => $theme]);
        $questionItems = [];
        foreach ($questions as $question) 
        {
            $questionItems[] = [
                'numb' => $question->getNumber(),
                'question' => $question->getQuestion(),
            ];
        }
        $paginate = $paginator->paginate(
            $questionItems,
            $request->query->getInt('page', 1),
            10
        ); 


        return $this->render('profile/choice.html.twig', [
            'themeName' => $themeName,
            'questions' => $paginate,
            'slug' => $slug,
        ]);
    }

    #[Route('/mes-themes/ajout/{slug}', name: 'ajout_question')]
    public function addQuestion(Request $request, EntityManagerInterface $emi, string $slug): Response
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

        // Récupération du nom du thème
        $themeName = $theme->getName();

        // Création d'une nouvelle question
        $question = new Questions();

        // Récupérer le numéro de question le plus grand
        $highestQuestionNumber = $emi->getRepository(Questions::class)->findHighestQuestionNumberForTheme($theme);

        // Définition du numéro de question
        if(!$highestQuestionNumber)
        {
            $numberQuestion = 1;
        }
        elseif($highestQuestionNumber < 30)
        {
            $numberQuestion = $highestQuestionNumber+1;
        }
        else
        {
            return $this->redirectToRoute('profil_themes');
        }

        // Définition de la difficulté
        if($numberQuestion <= 10)
        {
            $difficulte = 'Débutant';
        }
        elseif($numberQuestion > 10 && $numberQuestion <= 20)
        {
            $difficulte = 'Confirmé';
        }
        elseif($numberQuestion > 20)
        {
            $difficulte = 'Expert';
        }
        $difficulty = $emi->getRepository(Difficulties::class)->findOneBy(['name' => $difficulte]);
        
        
        // Création d'une nouvelle proposition
        $proposal = new Proposals();

        $addQuestionForm = $this->createForm(AddQuestionFormType::class, ['question' => $question, 'proposal' => $proposal]);
        
        // Traitement de la requête du formulaire
        $addQuestionForm->handleRequest($request);

        //Vérification si le formulaire est soumis ET valide
        if($addQuestionForm->isSubmitted() && $addQuestionForm->isValid())
        {
            // Définir le thème lié à la question
            $question->setIdTheme($theme);

            // Définir le numéro de la question
            $question->setNumber($numberQuestion);

            // Définir la difficulté
            $question->setIdDifficulty($difficulty);

            
            $answer = $question->getAnswer();
            $proposals = [
                $proposal->getFirstProposal(),
                $proposal->getSecondProposal(),
                $proposal->getThirdProposal(),
                $proposal->getFourthProposal(),
            ];
            if (!in_array($answer, $proposals)) {
                // La réponse ne correspond à aucune des propositions, gestion de l'erreur ou autre action à effectuer
                $this->addFlash('error', 'La réponse doit correspondre à l\'une des propositions.');
                return $this->render('profile/ajout_question.html.twig', [
                    'addQuestionForm' => $addQuestionForm->createView(),
                    'themeName' => $themeName,
                    'i' => $numberQuestion,
                    'difficulte' => $difficulte,
                ]);
            }
            //Stockage du formulaire question
            $emi->persist($question);
            $emi->flush();

            // On récupère l'id de la question qu'on vient de créer via l'objet question
            $questionId = $question->getId();
            $questionObject = $emi->getRepository(Questions::class)->find($questionId);

            $proposal->setIdQuestion($questionObject);

            // Stockage du formulaire proposal
            $emi->persist($proposal);
            $emi->flush();



            $this->addFlash('success','Theme créer avec succès');

            // Redirection en fonction du numéro de la question
            if($numberQuestion < 30)
            {
                return $this->redirectToRoute('profil_ajout_question', ['slug' => $theme->getSlug()]);
            }
            elseif($numberQuestion == 30)
            {
                // Mettre à jour le statut du thème en "Complet"
                $theme->setStatut('Complet');
                $emi->flush();

                return $this->redirectToRoute('profil_themes');
            }
        }


        return $this->render('profile/ajout_question.html.twig', [
            'addQuestionForm' => $addQuestionForm->createView(),
            'themeName' => $themeName,
            'i' => $numberQuestion,
            'difficulte' => $difficulte,
        ]);
    }


    #[Route('/mes-themes/edit/choice/{slug}/{numb}', name: 'edit_question')]
    public function editQuestion(
        Request $request, 
        EntityManagerInterface $emi, 
        string $slug,
        string $numb
        ): Response
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

        // Récupération du nom du thème
        $themeName = $theme->getName();

        // Récupération de la question
        $question = $emi->getRepository(Questions::class)->findOneBy(['theme' => $theme, 'number' => $numb]);
        
        // Récupération des propositions
        $proposal = $emi->getRepository(Proposals::class)->findOneBy(['question' => $question]);

        $questionDifficultyId = $question->getIdDifficulty();
        $difficulty = $emi->getRepository(Difficulties::class)->findOneBy(['id' => $questionDifficultyId]);
        $questionDifficulty = $difficulty->getName();

        $addQuestionForm = $this->createForm(AddQuestionFormType::class, ['question' => $question, 'proposal' => $proposal]);
        
        // Traitement de la requête du formulaire
        $addQuestionForm->handleRequest($request);

        //Vérification si le formulaire est soumis ET valide
        if($addQuestionForm->isSubmitted() && $addQuestionForm->isValid())
        {
            $answer = $question->getAnswer();
            $proposals = [
                $proposal->getFirstProposal(),
                $proposal->getSecondProposal(),
                $proposal->getThirdProposal(),
                $proposal->getFourthProposal(),
            ];
            if (!in_array($answer, $proposals)) {
                // La réponse ne correspond à aucune des propositions, gestion de l'erreur ou autre action à effectuer
                $this->addFlash('error', 'La réponse doit correspondre à l\'une des propositions.');
                return $this->render('profile/edit_question.html.twig', [
                    'addQuestionForm' => $addQuestionForm->createView(),
                    'themeName' => $themeName,
                    'numb' => $numb,
                    'difficulte' => $questionDifficulty,
                ]);
            }
            dd($question,$proposal);
            //Stockage du formulaire question
            $emi->persist($question);
            $emi->flush();

            // On récupère l'id de la question qu'on vient de créer via l'objet question
            $questionId = $question->getId();
            $questionObject = $emi->getRepository(Questions::class)->find($questionId);

            $proposal->setIdQuestion($questionObject);

            // Stockage du formulaire proposal
            $emi->persist($proposal);
            $emi->flush();



            $this->addFlash('success','Theme créer avec succès');

            // Mettre à jour le statut du thème en "Complet"
            $theme->setStatut('Complet');
            $emi->flush();

            return $this->redirectToRoute('profil_themes');
        }


        return $this->render('profile/edit_question.html.twig', [
            'addQuestionForm' => $addQuestionForm->createView(),
            'themeName' => $themeName,
            'numb' => $numb,
            'difficulte' => $questionDifficulty,
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
