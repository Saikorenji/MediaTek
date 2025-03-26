<?php
include_once "../utils/function.php";
include_once "../utils/regex.php";

startSecureSession();
include_once "./partials/top.php";

$errors = [];
$successes = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['secret_code']) && preg_match($validPatterns['six_digits_code'], $_POST['secret_code'])) {
        $secretCode = trim($_POST['secret_code']);

        if (isset($_SESSION['mfa_validation']) && $secretCode === $_SESSION['mfa_validation']) {
            unset($_SESSION['mfa_validation']); // Suppression du code MFA (sécurité)

            $_SESSION['is_logged'] = true; // Validation complète de l'utilisateur

            header("Location: welcome.php"); // ✅ Redirection immédiate
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

// Affichage des erreurs si besoin
if (!empty($errors)) {
    echo "<ul class='alert alert-danger'>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>";
}

include_once "./partials/bottom.php";
?>
