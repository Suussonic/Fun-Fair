<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($connexion)) {
    die("Erreur : La connexion à la base de données n'est pas définie.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $nom = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $note = htmlspecialchars($_POST['rating']);
    $contenu = htmlspecialchars($_POST['message']);


    $requete = "INSERT INTO Avis (nom, email, note, contenu) VALUES (?, ?, ?, ?)";
    if ($stmt = $connexion->prepare($requete)) {
        $stmt->bind_param('ssis', $nom, $email, $note, $contenu);
        if ($stmt->execute()) {
            $message = "Merci pour votre avis, $nom !";
        } else {
            $message = "Erreur lors de l'envoi de votre avis. Veuillez réessayer.";
        }
        $stmt->close();
    } else {
        $message = "Erreur de préparation de la requête.";
    }
}


$requete = "SELECT nom, note, contenu, created_at FROM Avis ORDER BY created_at DESC";
$resultat = $connexion->query($requete);


require 'views/avis.view.php';


$connexion->close();
?>
