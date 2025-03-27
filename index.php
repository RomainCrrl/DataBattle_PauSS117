<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Question aléatoire</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-gray-200">
    <?php include 'sidebar.php'; ?>
    <?php include 'header.php'; ?>

    <!-- No mode selection buttons – the question is chosen randomly -->
    <div class="main-content">
        <div class="chat-container bg-gray-800 border border-gray-700 rounded-lg shadow-md">
            <div id="chat-box" class="overflow-y-auto p-4 flex flex-col-reverse"></div>
            <div class="chat-input flex border-t border-gray-700 p-2">
                <input type="text" id="message-input" placeholder="Écris un message..." autofocus class="flex-grow border border-gray-700 rounded-md py-2 px-3 bg-gray-700 text-gray-200">
                <button onclick="sendMessage()" class="ml-2 px-4 py-2 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">Envoyer</button>
            </div>
        </div>
    </div>

    <script>
        // Change this value to match the total number of themes available.
        const numberOfThemes = 13;
        const types = ["open", "qcm"];

        // This function returns a random integer between min and max (inclusive)
        function getRandomInt(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        // Returns a random theme number and random type
        function getRandomParameters() {
            let randomTheme = getRandomInt(1, numberOfThemes);
            let randomType = types[getRandomInt(0, types.length - 1)];
            return { theme: randomTheme, type: randomType };
        }

        // Append a message to the chat history
        function appendMessage(text, sender) {
            let chatBox = document.getElementById("chat-box");
            let messageElement = document.createElement("div");
            messageElement.classList.add("message", sender === "bot" ? "bot-message" : "user-message");
            messageElement.innerText = text;
            chatBox.prepend(messageElement);
        }

        // getRandomQuestion() chooses random parameters and then calls the /ask endpoint.
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

        // sendMessage() processes the answer and then always loads a new random question.
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

        // For this random-question page, we always start with a random question.
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
