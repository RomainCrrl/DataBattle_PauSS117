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
    <title>Accueil</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-gray-200">
    <?php include 'sidebar.php'; ?>
    <?php include 'header.php'; ?>

    <!-- Boutons de sélection de mode -->
    <div class="mode-buttons flex justify-center p-4">
        <button id="open-btn" onclick="startMode('open')" class="mx-2 px-4 py-2 bg-green-500 text-white rounded-md">Open</button>
        <button id="qcm-btn" onclick="startMode('qcm')" class="mx-2 px-4 py-2 bg-blue-500 text-white rounded-md">QCM</button>
    </div>

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
        let currentMode = ""; // 'open' ou 'qcm'
        
        function appendMessage(text, sender) {
            let chatBox = document.getElementById("chat-box");
            let messageElement = document.createElement("div");
            messageElement.classList.add("message", sender === "bot" ? "bot-message" : "user-message");
            messageElement.innerText = text;
            chatBox.prepend(messageElement);
        }
        
        function startMode(mode) {
            currentMode = mode;
            // Réinitialiser la conversation
            document.getElementById("chat-box").innerHTML = "";
            appendMessage("Mode sélectionné : " + mode.toUpperCase(), "bot");
            getQuestion();
        }
        
        function getQuestion() {
            fetch('http://localhost:5000/ask?theme=1&type=' + currentMode)
                .then(response => response.json())
                .then(data => {
                    console.log("Réponse de /ask:", data);
                    if (currentMode === 'open') {
                        appendMessage("Statement: " + data.enonce, "bot");
                        appendMessage(data.question, "bot");
                    } else if (currentMode === 'qcm') {
                        appendMessage("Statement: " + data.enonce, "bot");
                        let answers = data.answers;
                        let optionsText = "";
                        for (let option in answers) {
                            optionsText += option + ": " + answers[option] + "    ";
                        }
                        appendMessage("Options: " + optionsText, "bot");
                    }
                })
                .catch(error => console.error('Erreur dans getQuestion:', error));
        }
        
        function sendMessage() {
            let inputField = document.getElementById("message-input");
            let userMessage = inputField.value.trim();
            if (userMessage === "") return;
            appendMessage(userMessage, "user");
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
                if (data.next) {
                    setTimeout(nextQuestion, 1000);
                } else {
                    setTimeout(getQuestion, 2000);
                }
            })
            .catch(error => console.error('Erreur dans sendMessage:', error));
        }
        
        function nextQuestion() {
            fetch('http://localhost:5000/next')
                .then(response => response.json())
                .then(data => {
                    console.log("Réponse de /next:", data);
                    if (data.done) {
                        setTimeout(getQuestion, 2000);
                    } else {
                        if (currentMode === 'open') {
                            appendMessage("Next Question", "bot");
                            appendMessage(data.question, "bot");
                        } else if (currentMode === 'qcm') {
                            appendMessage("Next Question", "bot");
                            appendMessage("Statement: " + data.enonce, "bot");
                            let answers = data.answers;
                            let optionsText = "";
                            for (let option in answers) {
                                optionsText += option + ": " + answers[option] + "    ";
                            }
                            appendMessage("Options: " + optionsText, "bot");
                        }
                    }
                })
                .catch(error => console.error('Erreur dans nextQuestion:', error));
        }
        
        document.getElementById("message-input").addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                sendMessage();
            }
        });
        
        // Message initial invitant à choisir un mode
        window.onload = function() {
            appendMessage("Veuillez sélectionner un mode : OPEN ou QCM", "bot");
        }
    </script>
</body>
</html>
