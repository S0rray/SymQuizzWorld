// Récup des données via le controller
var url = window.location.href + '/data';
console.log(url);

fetch(url)
    .then(response => {
        if(!response.ok) {
            throw new Error('Erreur lors de la récupération des données');
        }
        return response.json();
    })
    .then(data => {
        console.log(data);
        console.log(data.user);
        console.log(data.theme);
        console.log(data.difficulty);
        console.log(data.question[0].question);
        console.log(data.question[0].answer);
        console.log(data.question[0].anecdote);
        console.log(data.proposal[0].firstProposal);
        console.log(data.proposal[0].secondProposal);
        console.log(data.proposal[0].thirdProposal);
        console.log(data.proposal[0].fourthProposal);


        // ----- PARTIE 1 [RECAP] ----- //
        // Div principal qui va nous servir pour remplir la page
        var containerQuizz = document.getElementById('quizzContent');

        // Récupération des infos
        // var questionDataElement = document.getElementById('questionsData');
        // var proposalDataElement = document.getElementById('proposalsData');

        // var questionData = JSON.parse(questionDataElement.innerHTML);
        // var proposalData = JSON.parse(proposalDataElement.innerHTML);

        // var username = document.getElementById('username').getAttribute('data-username');
        // var pictureURL1 = document.getElementById('srcPic').getAttribute('data-src');
        console.log(pictureURL);
        
        var username = data.user;
        var picture = data.picture;
        var pictureURL = `/assets/uploads/themes/${picture}`;
        console.log(pictureURL);

        // Remplissage de la page pour l'intro au quizz
        containerQuizz.innerHTML = `
        <h2><span class="userIntro">${username}</span>, vous allez pouvoir démarrer ce Quizz</h2>
        <img src="${pictureURL}" class="imgIntro mb-2" alt="Image d'illustration">
        <a class="btn btn-primary btn-lg mt-2" id="btnStart">Démarrer le quizz</a>
        `;

        // ----- PARTIE 2 [QUIZZ] ----- //

        // Action lors de l'appui sur le bouton pour démarrer le quizz
        $('#btnStart').on('click', function() 
        {
            containerQuizz.innerHTML = `
            <h2>Question n°<span id="questionNumberQuizz"></span> : <span class="questionLabel" id="questionText"></span></h2>
            <br><br><br>
            <div class="flex-row w-100">
                <div class="proposals-container">
                    <span class="btn btn-info col-2 m-2 draggable" id="proposal1"></span>
                    <span class="btn btn-info col-2 m-2 draggable" id="proposal2"></span>
                    <span class="btn btn-info col-2 m-2 draggable" id="proposal3"></span>
                    <span class="btn btn-info col-2 m-2 draggable" id="proposal4"></span>
                </div>
            </div>
            <h5 class="mb-2" id=anecdoteRow><span class="anecdote">Anecdote : </span><span id="anecdoteLabel"></span></h5>
            <div class="row justify-content-center w-100">
                <div id="answerArea" class="answerArea" class="col-3"></div>
                <div class="flex-column col-5">
                    <a class="btn btn-primary btn-lg mt-2" id="btnNext">Question suivante</a>
                    <span id="resultText" class="col-12"></span>
                </div>
            </div>
            `;
            // Récupération des éléments à compléter
            var questionNumberQuizz = document.getElementById('questionNumberQuizz');
            var questionText = document.getElementById('questionText');
            var proposal1 = document.getElementById('proposal1');
            var proposal2 = document.getElementById('proposal2');
            var proposal3 = document.getElementById('proposal3');
            var proposal4 = document.getElementById('proposal4');
            var resultText = document.getElementById('resultText');
            var anecdoteLabel = document.getElementById('anecdoteLabel');
            var anecdoteRow = document.getElementById('anecdoteRow');
            anecdoteRow.style.visibility = 'hidden';

            quizz();
            // Action lors de l'appui sur le bouton suivant
            $('#btnNext').on('click', function() 
            {
                if (dropCompleted)
                {
                    i++;
                    numb++;
                    if(i < 10)
                    {
                        quizz();
                    }
                    else
                    {
                        resultat();
                    }
                    
                } else 
                {
                    $('#modalError').modal('show');
                }
            });
        });

        // Initialisation des compteurs
        var i = 0;
        var numb = 1;
        var score = 0;

        // Variable de vérification de réponse
        var dropCompleted = false;

        // Fonction pour gérer le déroulement du quizz
        function quizz()
        {
            if(dropCompleted === true)
            {
                resetQuizz();
            }

            generateQuizz();

            dropCompleted = false;

            // Rendre les éléments draggable
            if(!dropCompleted) 
            {
                $('.draggable').draggable({
                    revert: 'invalid', // Renvoyer à la position d'origine si non déposé dans une zone valide
                    cursor: 'url(/assets/img/cursor_main_mini.png), auto', // Utiliser une image personnalisée comme curseur
                });
            }

            // Rendre la zone de réponse droppable
            $('#answerArea').droppable({
                accept: '.draggable', // Accepter uniquement les éléments avec la classe 'draggable'
                drop: function(event, ui) {
                    if(!dropCompleted)
                    {
                        var droppedElement = ui.draggable.text(); // Récupérer l'ID de l'élément déposé
                        if (droppedElement == data.question[i].answer) // Cas où la proposition déposée est la bonne réponse
                        {
                            $(this).css('background-color', 'green'); // Changer la couleur de fond de la zone de réponse en vert (bonne réponse)
                            ui.draggable.css('background-color', '#1fff00');
                            resultText.classList.add('text-success');
                            resultText.innerHTML = 'Réponse correcte !';
                            anecdoteLabel.innerHTML = data.question[i].anecdote;
                            anecdoteRow.style.visibility = 'visible';
                            score++;
                        } 
                        else // Cas où la proposition déposée est la mauvaise réponse
                        {
                            $(this).css('background-color', 'red'); // Changer la couleur de fond de la zone de réponse en rouge (mauvaise réponse)
                            ui.draggable.css('background-color', '#ff0000');
                            resultText.classList.add('text-danger');
                            resultText.innerHTML = 'Réponse fausse !';

                            $('.draggable').each(function() {
                                if ($(this).text() == data.question[i].answer) {
                                    $(this).css('background-color', '#1fff00');
                                }
                            });

                        }
                        dropCompleted = true;
                    }
                }
            });
        }

        function generateQuizz()
        {
            // Afficher les données
            questionNumberQuizz.innerHTML = numb;
            questionText.textContent = data.question[i].question;
            proposal1.textContent = data.proposal[i].firstProposal;
            proposal2.textContent = data.proposal[i].secondProposal;
            proposal3.textContent = data.proposal[i].thirdProposal;
            proposal4.textContent = data.proposal[i].fourthProposal;
        }

        function resetQuizz() 
        {
            $('.draggable').css('background-color', ''); // Réinitialiser la couleur de fond des éléments draggable
            $('#answerArea').css('background-color', ''); // Réinitialiser la couleur de fond de la zone droppable
            $('.draggable').each(function() {
                $(this).draggable('destroy'); // Supprimer le comportement draggable
                $(this).css({ // Réinitialiser la position initiale
                    top: 0,
                    left: 0
                });
            });
            resultText.innerHTML = '';
            anecdoteLabel.innerHTML = '';
            if(resultText.classList.contains('text-success'))
            {
                anecdoteRow.style.visibility = 'hidden';
                resultText.classList.remove('text-success');
            }
            if(resultText.classList.contains('text-danger'))
            {
                resultText.classList.remove('text-danger');
            }
        }

        // ----- PARTIE 3 [RESULTAT] ----- //

        function resultat()
        {
            containerQuizz.innerHTML = `
                <h2>Quizz Terminé !!</h2>
                <br><br>
                <h2><span class="userIntro">${username}</span>, vous avez obtenu le score de <span class="text-info">${score}/10</span></h2>
                <br><br><br><br><br>
                <a href="/" class="btn btn-lg btn-primary mt-2">Accueil</a>
            `;
        }


    })
    .catch(err => {console.log(err)});


