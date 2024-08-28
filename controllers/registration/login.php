<?php
include_once('models/Database.php');

$errorInfo = false;

// Vérifier si la connexion à la base de données est établie
if (!isset($pdo)) {
    die('Connexion à la base de données échouée.');
}

// Vérifier si le formulaire a été soumis
if (isset($_POST['email']) && isset($_POST['password'])) {
    // Sanitize les données de l'utilisateur
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Préparer la requête pour récupérer l'utilisateur par email
    $loginSql = 'SELECT * FROM users WHERE email = :email';

    try {
        $preparedLoginRequest = $pdo->prepare($loginSql);
        $preparedLoginRequest->execute(['email' => $email]);
    } catch (PDOException $e) {
        die('Erreur lors de l\'exécution de la requête SQL : ' . $e->getMessage());
    }

    // Récupérer l'utilisateur depuis la base de données
    $user = $preparedLoginRequest->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'utilisateur existe et si le mot de passe est correct
    if ($user && password_verify($password, $user['password'])) {
        session_start();
        // Stocker les informations utilisateur dans la session
        $_SESSION['id'] = $user['id'];  // Utilisez 'id' de manière cohérente
        $_SESSION['firstname'] = $user['firstname'];
        $_SESSION['lastname'] = $user['lastname'];
        $_SESSION['user'] = $user;

        header('Location: /'); // Rediriger vers la page d'accueil
        exit;
    } else {
        $errorInfo = true;
    }
}

require 'views/registration/login.view.php';
?>
