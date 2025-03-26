<?php
include_once "../utils/function.php";
startSecureSession();

include_once "../utils/regex.php";
include_once "./partials/top.php";

$errors = [];
$successes = [];

// [1] Vérification du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['secret_code']) && preg_match($validPatterns['six_digits_code'], $_POST['secret_code'])) {
        $secretCode = trim($_POST['secret_code']);

        if (isset($_SESSION['mfa_validation']) && $secretCode === $_SESSION['mfa_validation']) {
            // Authentification MFA réussie
            unset($_SESSION['mfa_validation']);
            $successes[] = "Authentification validée.";

            // Redirection vers la page d'accueil personnalisée
            header("Location: welcome.php");
            exit;
        } else {
            $errors[] = "Le code secret saisi est incorrect.";
        }
    } else {
        $errors[] = "Le champ 'Code secret' est requis et doit contenir exactement 6 chiffres.";
    }
} else {
    $_SESSION = [];
    session_destroy();
    header('Location: ../405.php');
    exit;
}

// [2] Affichage des résultats
if (!empty($errors)) {
    echo "<ul class='alert alert-danger'>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>";
}

include_once "./partials/bottom.php";
?>
