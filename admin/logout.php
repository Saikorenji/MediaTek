<?php

include_once "../utils/function.php";

startSecureSession();

// Vider la session
$_SESSION = [];
session_destroy();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="3;url=login_form.php">
    <title>Déconnexion</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Si tu as un fichier CSS -->
    <style>
        body {
            background-color: #1d1f27;
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .logout-message {
            background: #2a2d3a;
            padding: 2rem 3rem;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="logout-message">
        <h2>Déconnexion réussie</h2>
        <p>Vous allez être redirigé vers la page de connexion...</p>
    </div>
</body>
</html>
