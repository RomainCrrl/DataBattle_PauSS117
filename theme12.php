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
    <title>Test your knowledge on Theme 12</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'sidebar.php'; ?> 
    <?php include 'header.php'; ?>

    <div class="main-content">
        <h2 class="p-2 text-center font-bold text-3xl">Test your knowledge on the 12th theme</h2>
        
        <div class="mode-buttons flex justify-center p-4">
            <button id="open-btn" onclick="startMode('open')" class="mx-2 px-4 py-2 bg-green-500 text-white rounded-md">Open</button>
            <button id="qcm-btn" onclick="startMode('qcm')" class="mx-2 px-4 py-2 bg-blue-500 text-white rounded-md">QCM</button>
        </div>

        <div class="chat-container">
            <div id="chat-box"></div>
            <div class="chat-input">
                <input type="text" id="message-input" placeholder="Write a message..." autofocus>
                <button onclick="sendMessage()">Send</button>
            </div>
        </div>
    </div>

    <script>
    	document.getElementById('theme-toggle').addEventListener('click', function() {
    	document.body.classList.toggle('dark-mode'); // Change 'dark' en 'dark-mode'
	});
	
        let currentMode = ""; // 'open' or 'qcm'
        let openStatementComplete = false; // Flag for OPEN mode statement completion

        // Append a message to the chat history
        function appendMessage(text, sender) {
            let chatBox = document.getElementById("chat-box");
            let messageElement = document.createElement("div");
            messageElement.classList.add("message", sender === "bot" ? "bot-message" : "user-message");
            messageElement.innerText = text;
            chatBox.prepend(messageElement);
        }

        // For QCM mode, format and append the question text (filtering out empty options)
        function displayQcmQuestion(enonce, answers) {
            let optionsText = "";
            const keys = ["A", "B", "C", "D", "E", "F"];
            keys.forEach(option => {
                let answerText = answers[option] ? answers[option].trim() : "";
                if(answerText !== ""){
                    optionsText += option + ": " + answerText + "    ";
                }
            });
            appendMessage("Statement: " + enonce + "\nOptions: " + optionsText, "bot");
        }

        function startMode(mode) {
            currentMode = mode;
            openStatementComplete = false; // reset flag
            // Clear chat box
            document.getElementById("chat-box").innerHTML = "";
            appendMessage("Mode sélectionné : " + mode.toUpperCase(), "bot");
            getQuestion();
        }

        // getQuestion() loads the first question (or new statement) for both modes
        function getQuestion() {
            fetch('http://localhost:5000/ask?theme=12&type=' + currentMode + "&_=" + new Date().getTime())
                .then(response => response.json())
                .then(data => {
                    console.log("Réponse de /ask:", data);
                    if (currentMode === 'open') {
                        appendMessage("Statement: " + data.enonce, "bot");
                        appendMessage("Question: " + data.question, "bot");
                    } else if (currentMode === 'qcm') {
                        displayQcmQuestion(data.enonce, data.answers);
                    }
                })
                .catch(error => console.error('Erreur in getQuestion:', error));
        }

        // sendMessage() handles the answer and then:
        // - In QCM mode: always calls nextQuestion()
        // - In OPEN mode: if the statement is complete, treats the input as a trigger for a new statement.
        function sendMessage() {
            let inputField = document.getElementById("message-input");
            let userMessage = inputField.value.trim();
            if (userMessage === "") return;
            
            // In OPEN mode, if statement is complete, use the input to trigger a new statement.
            if (currentMode === 'open' && openStatementComplete) {
                appendMessage("New statement confirmed. Loading new statement...", "bot");
                openStatementComplete = false;
                inputField.value = "";
                getQuestion();
                return;
            }
            
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
                appendMessage("Bot: " + data.result, "bot");
                if (data.explanation) {
                    appendMessage("Explication: " + data.explanation, "bot");
                }
                if (currentMode === 'qcm') {
                    setTimeout(nextQuestion, 1000);
                } else { // OPEN mode
                    if (data.next) {
                        setTimeout(nextQuestion, 1000);
                    } else {
                        appendMessage("Statement complete. New statement? (Type anything to continue)", "bot");
                        openStatementComplete = true;
                    }
                }
            })
            .catch(error => console.error('Erreur in sendMessage:', error));
        }

        // nextQuestion() fetches the next question for both modes.
        function nextQuestion() {
            fetch('http://localhost:5000/next?_=' + new Date().getTime())
                .then(response => response.json())
                .then(data => {
                    console.log("Réponse de /next:", data);
                    if (data.done) {
                        // For OPEN mode, if no more questions, prompt for new statement.
                        if (currentMode === 'open') {
                            appendMessage("Statement complete. New statement? (Type anything to continue)", "bot");
                            openStatementComplete = true;
                        } else { // For QCM, reload a new statement automatically after a delay.
                            setTimeout(getQuestion, 2000);
                        }
                    } else {
                        if (currentMode === 'open') {
                            // If the next question is empty, treat as statement complete.
                            if (!data.question || data.question.trim() === "") {
                                appendMessage("Statement complete. New statement? (Type anything to continue)", "bot");
                                openStatementComplete = true;
                            } else {
                                appendMessage("Next Question: " + data.question, "bot");
                            }
                        } else if (currentMode === 'qcm') {
                            displayQcmQuestion(data.enonce, data.answers);
                        }
                    }
                })
                .catch(error => console.error('Erreur in nextQuestion:', error));
        }

        window.onload = function() {
            appendMessage("Veuillez sélectionner un mode : OPEN ou QCM", "bot");
        }
    </script>
</body>
</html>
