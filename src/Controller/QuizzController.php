<?php

namespace App\Controller;

use App\Entity\Difficulties;
use App\Entity\Proposals;
use App\Entity\Questions;
use App\Entity\Themes;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/quizz', name: 'quizz_')]
class QuizzController extends AbstractController
{
    #[Route('/{slug}/difficulte_{name}', name: 'start')]
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
                $difficultyId = 1;
                $finalQuestion = 10;
            }
            if($name === "Confirmé")
            {
                $questionNumb = 11;
                $difficultyId = 2;
                $finalQuestion = 20;
            }
            if($name === "Expert")
            {
                $questionNumb = 21;
                $difficultyId = 3;
                $finalQuestion = 30;
            }
        }
        if($answerSubmit === 1)
        {
            $questionNumb++;
            $answerSubmit = 0;
        }

        // Récupère l'ID de la difficulté
        if($name === "Débutant")
            {
                $difficultyId = 1;
            }
            if($name === "Confirmé")
            {
                $difficultyId = 2;
            }
            if($name === "Expert")
            {
                $difficultyId = 3;
            }

        // Stockage de la question dans la session
        $session->set('question_number', $questionNumb);

        // Récupération des thèmes en état complet
        $theme = $emi->getRepository(Themes::class)->findOneBy(['slug' => $slug]);

        // Récupération de la question correspondante
        $questions = $emi->getRepository(Questions::class)->findBy(['difficulty' => $difficultyId, 'theme' => $theme]);

        // Récupération des propositions correspondantes
        $proposals = $emi->getRepository(Proposals::class)->findBy(['question' => $questions]);

        // Transformation de l'objet en tableau avec les éléments dont on a besoin
        $arrayQuestions = [];
        foreach ($questions as $question) {
            $arrayQuestion = [
                'number' => $question->getNumber(),
                'question' => $question->getQuestion(),
                'answer' => $question->getAnswer(),
                'anecdote' => $question->getAnecdote(),
            ];
            $arrayQuestions[] = $arrayQuestion;
        }

        $arrayProposals = [];
        foreach ($proposals as $proposal) {
            $arrayProposal = [
                'idQuestion' => $proposal->getIdQuestion()->getId(),
                'firstProposal' => $proposal->getFirstProposal(),
                'secondProposal' => $proposal->getSecondProposal(),
                'thirdProposal' => $proposal->getThirdProposal(),
                'fourthProposal' => $proposal->getFourthProposal(),
            ];
            $arrayProposals[] = $arrayProposal;
        }


        // Encodage en json des tableau questions et proposals
        $jsonQuestions = json_encode($arrayQuestions, JSON_UNESCAPED_UNICODE);
        $jsonProposals = json_encode($arrayProposals, JSON_UNESCAPED_UNICODE);

        return $this->render('quizz/question.html.twig', [
            'difficulty' => $name,
            'theme' => $theme,
            'question' => $jsonQuestions,
            'proposal' => $jsonProposals,
            'numb' => $questionNumb
        ]);
    }

    #[Route('/{slug}/difficulte_{name}/data', name: 'get_quizz_data')]
    public function getQuizzData(
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
        
        $user = $this->getUser();
        $userdata = $emi->getRepository(Users::class)->findOneBy(['id' => $user]);
        $username = $userdata->getUsername();
        $difficulty = $emi->getRepository(Difficulties::class)->findOneBy(['name' => $name]);
        $difficultyId = $difficulty->getId();
        $theme = $emi->getRepository(Themes::class)->findOneBy(['slug' => $slug]);
        $themeName = $theme->getName();
        $themePicture = $theme->getPicture();
        // Récupération de la question correspondante
        $questions = $emi->getRepository(Questions::class)->findBy(['difficulty' => $difficultyId, 'theme' => $theme]);

        // Récupération des propositions correspondantes
        $proposals = $emi->getRepository(Proposals::class)->findBy(['question' => $questions]);

        // Transformation de l'objet en tableau avec les éléments dont on a besoin
        $arrayQuestions = [];
        foreach ($questions as $question) {
            $arrayQuestion = [
                'number' => $question->getNumber(),
                'question' => $question->getQuestion(),
                'answer' => $question->getAnswer(),
                'anecdote' => $question->getAnecdote(),
            ];
            $arrayQuestions[] = $arrayQuestion;
        }

        $arrayProposals = [];
        foreach ($proposals as $proposal) {
            $arrayProposal = [
                'idQuestion' => $proposal->getIdQuestion()->getId(),
                'firstProposal' => $proposal->getFirstProposal(),
                'secondProposal' => $proposal->getSecondProposal(),
                'thirdProposal' => $proposal->getThirdProposal(),
                'fourthProposal' => $proposal->getFourthProposal(),
            ];
            $arrayProposals[] = $arrayProposal;
        }

        $data = [
            'user' => $username,
            'theme' => $themeName,
            'picture' => $themePicture,
            'difficulty' => $name,
            'question' => $arrayQuestions,
            'proposal' => $arrayProposals,
        ];

        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
        // dd($jsonData);
        return new JsonResponse($data);


    }
}
