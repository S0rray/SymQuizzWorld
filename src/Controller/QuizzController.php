<?php

namespace App\Controller;

use App\Entity\Difficulties;
use App\Entity\Proposals;
use App\Entity\Questions;
use App\Entity\Themes;
use App\Repository\DifficultiesRepository;
use App\Repository\ThemesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/quizz', name: 'quizz_')]
class QuizzController extends AbstractController
{
    #[Route('/{slug}/difficulte_{name}', name: 'start')]
    public function start(
        EntityManagerInterface $emi, 
        string $slug, 
        string $name,
        ): Response
    {
        // Vérifier si l'utilisateur est connecté ou non
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
            return $this->redirectToRoute('app_login');
        }

        // Récupération des thèmes en état complet
        $theme = $emi->getRepository(Themes::class)->findBy(['slug' => $slug]);

        return $this->render('quizz/start.html.twig', [
            'difficulty' => $name,
            'theme' => $theme,
        ]);
    }

    #[Route('/{slug}/difficulte_{name}/quizz', name: 'question')]
    public function questionQuizz(
        EntityManagerInterface $emi, 
        SessionInterface $session,
        string $slug, 
        string $name,
        ): Response
    {
        // Vérifier si l'utilisateur est connecté ou non
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
            return $this->redirectToRoute('app_login');
        }
        
        // On récupère le numéro de la question ou on le défini en fonction de la difficulté
        $questionNumb = $session->get('question_number', null);
        
        // On vérifie si un choix a été fait
        $answerSubmit = $session->get('answer_submit', 0);

        if($questionNumb === null)
        {
            if($name === "Débutant")
            {
                $questionNumb = 1;
                $finalQuestion = 10;
            }
            if($name === "Confirmé")
            {
                $questionNumb = 11;
                $finalQuestion = 20;
            }
            if($name === "Expert")
            {
                $questionNumb = 21;
                $finalQuestion = 30;
            }
        }
        if($answerSubmit === 1)
        {
            $questionNumb++;
            $answerSubmit = 0;
        }

        // Stockage de la question dans la session
        $session->set('question_number', $questionNumb);

        // Récupération des thèmes en état complet
        $theme = $emi->getRepository(Themes::class)->findBy(['slug' => $slug]);

        // Récupération de la question correspondante
        $question = $emi->getRepository(Questions::class)->findOneBy(['number' => $questionNumb, 'theme' => $theme]);

        // Récupération des propositions correspondantes
        $proposals = $emi->getRepository(Proposals::class)->findOneBy(['question' => $question]);

        return $this->render('quizz/question.html.twig', [
            'difficulty' => $name,
            'theme' => $theme,
            'question' => $question,
            'proposals' => $proposals,
            'numb' => $questionNumb
        ]);
    }

    #[Route('/{slug}/difficulte_{name}/reponse', name: 'reponse')]
    public function reponseQuizz(
        EntityManagerInterface $emi, 
        SessionInterface $session,
        string $slug, 
        string $name,
        ): Response
    {
        // Vérifier si l'utilisateur est connecté ou non
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
            return $this->redirectToRoute('app_login');
        }
        
        // On récupère le numéro de la question ou on le défini en fonction de la difficulté
        $questionNumb = $session->get('question_number');

        // Récupération des thèmes en état complet
        $theme = $emi->getRepository(Themes::class)->findBy(['slug' => $slug]);

        // Récupération de la question correspondante
        $question = $emi->getRepository(Questions::class)->findOneBy(['number' => $questionNumb, 'theme' => $theme]);

        // Récupération des propositions correspondantes
        $proposals = $emi->getRepository(Proposals::class)->findOneBy(['question' => $question]);

        return $this->render('question/start.html.twig', [
            'difficulty' => $name,
            'theme' => $theme,
            'question' => $question,
            'proposals' => $proposals,
            'numb' => $questionNumb
        ]);
    }
}
