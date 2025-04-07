<?php
include_once "../utils/function.php";

startSecureSession();

// Nettoyage complet
$_SESSION = [];
session_unset();
session_destroy();

// Redémarre une session uniquement pour le message
session_start();
$_SESSION['logout_success'] = "Vous avez été déconnecté avec succès.";

// Redirection vers la page d’accueil
header("Location: ../index.php");
exit;
