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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Simple</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>  <!-- Barre latérale -->
    <?php include 'header.php'; ?>   <!-- Bandeau du haut -->
    
    <div class="chat-container">
        <div id="chat-box"></div> <!-- Zone d'affichage des messages -->
        <div class="chat-input">
            <input type="text" id="message-input" placeholder="Écris un message..." autofocus>
            <button onclick="sendMessage()">Envoyer</button>
        </div>
    </div>

    <script>
        function sendMessage() {
            let inputField = document.getElementById("message-input");
            let message = inputField.value.trim();
            if (message === "") return; // Empêche l'envoi d'un message vide

            let chatBox = document.getElementById("chat-box");

            // Ajouter le message utilisateur
            let userMessage = document.createElement("div");
            userMessage.classList.add("message", "user-message");
            userMessage.innerText = message;
            chatBox.prepend(userMessage);

            // Ajouter la réponse automatique
            let botMessage = document.createElement("div");
            botMessage.classList.add("message", "bot-message");
            botMessage.innerText = "C'est compris";
            setTimeout(() => {
                chatBox.prepend(botMessage);
            }, 500); // Délai pour un effet plus réaliste

            inputField.value = ""; // Réinitialiser l'input
        }
    </script>
</body>
</html>
