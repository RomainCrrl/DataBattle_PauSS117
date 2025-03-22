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
    <title>Accueil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>  <!-- Barre latérale -->
    <?php include 'header.php'; ?>   <!-- Bandeau du haut -->
    <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h1>
</body>
</html>


