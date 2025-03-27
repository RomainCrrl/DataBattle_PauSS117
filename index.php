<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Question aléatoire</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <?php include 'header.php'; ?>

    <div class="main-content">
    	<h2 class="p-2 text-center font-bold text-3xl">Test your knowledge on the 13 themes</h2>
        <div class="chat-container">
            <div id="chat-box" class="overflow-y-auto p-4 flex flex-col-reverse"></div>
            <div class="chat-input flex border-t p-2">
                <input type="text" id="message-input" placeholder="Write a message..." autofocus>
                <button onclick="sendMessage()">Send</button>
            </div>
        </div>
    </div>

    <script>
    	document.getElementById('theme-toggle').addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
        });
  
        const numberOfThemes = 13;
        const types = ["open", "qcm"];

       
        function getRandomInt(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

     
        function getRandomParameters() {
            let randomTheme = getRandomInt(1, numberOfThemes);
            let randomType = types[getRandomInt(0, types.length - 1)];
            return { theme: randomTheme, type: randomType };
        }

       
        function appendMessage(text, sender) {
            let chatBox = document.getElementById("chat-box");
            let messageElement = document.createElement("div");
            messageElement.classList.add("message", sender === "bot" ? "bot-message" : "user-message");
            messageElement.innerText = text;
            chatBox.prepend(messageElement);
        }

       
        function getRandomQuestion() {
            const params = getRandomParameters();
            // Display which theme and type were chosen (optional debugging)
            console.log("Random parameters: theme=" + params.theme + ", type=" + params.type);
            fetch('http://localhost:5000/ask?theme=' + params.theme + '&type=' + params.type + "&_=" + new Date().getTime())
                .then(response => response.json())
                .then(data => {
                    console.log("Réponse de /ask:", data);
                    if (params.type === 'open') {
                        appendMessage("Statement (Theme " + params.theme + " - OPEN): " + data.enonce, "bot");
                        appendMessage("Question: " + data.question, "bot");
                    } else if (params.type === 'qcm') {
                        appendMessage("Statement (Theme " + params.theme + " - QCM): " + data.enonce, "bot");
                        let answers = data.answers;
                        let optionsText = "";
                        for (let option in answers) {
                            optionsText += option + ": " + answers[option] + "    ";
                        }
                        appendMessage("Options: " + optionsText, "bot");
                    }
                })
                .catch(error => console.error('Erreur dans getRandomQuestion:', error));
        }

        
        function sendMessage() {
            let inputField = document.getElementById("message-input");
            let userMessage = inputField.value.trim();
            if (userMessage === "") return;
            appendMessage("Tu: " + userMessage, "user");
            inputField.value = "";

            fetch('http://localhost:5000/answer', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ answer: userMessage })
            })
            .then(response => response.json())
            .then(data => {
                console.log("Réponse de /answer:", data);
                appendMessage(data.result, "bot");
                if(data.explanation) {
                    appendMessage("Explication: " + data.explanation, "bot");
                }
                // After an answer, wait a moment and then load a new random question.
                setTimeout(getRandomQuestion, 1500);
            })
            .catch(error => console.error('Erreur dans sendMessage:', error));
        }

       
        window.onload = function() {
            appendMessage("Génération d'une question aléatoire...", "bot");
            getRandomQuestion();
            document.getElementById("message-input").addEventListener("keypress", function(event) {
                if (event.key === "Enter") {
                    sendMessage();
                }
            });
        }
    </script>
</body>
</html>
