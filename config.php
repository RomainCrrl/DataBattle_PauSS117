<?php
session_start();

$host = "localhost";
$dbname = "dataBattle";
$username = "romain"; // Change si besoin
$password = "bddromain"; // Ajoute ton mot de passe si nécessaire

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
