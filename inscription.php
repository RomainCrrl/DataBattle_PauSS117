<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    if ($stmt->execute([$username, $password])) {
        echo "Inscription réussie ! <a href='connexion.php'>Connecte-toi ici</a>";
    } else {
        echo "Erreur lors de l'inscription.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-gray-200">
    <div class="flex items-center justify-center min-h-screen">
        <form action="" method="POST" class="bg-gray-800 p-8 rounded-md shadow-md w-96">
            <h2 class="text-2xl font-bold mb-6 text-center">Inscription</h2>
            <div class="mb-4">
                <label for="username" class="block text-gray-300 text-sm font-bold mb-2">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" placeholder="Nom d'utilisateur" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-700 text-gray-200">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-300 text-sm font-bold mb-2">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Mot de passe" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-700 text-gray-200">
            </div>
            <div class="flex flex-col items-center justify-center">
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4 w-full">
                    S'inscrire
                </button>
                <a href="connexion.php" class="inline-block align-baseline font-bold text-sm text-indigo-500 hover:text-indigo-800">
                    Déjà un compte ? Se connecter
                </a>
            </div>
        </form>
    </div>
</body>
</html>