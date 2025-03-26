<?php

include_once "../utils/function.php";

startSecureSession(); // Si nécessaire

// Supprimer toutes les variables de session
$_SESSION = [];

// Détruire la session
session_destroy();

// Rediriger vers la page d’accueil ou de connexion
header('Location: login_form.php');
exit;
