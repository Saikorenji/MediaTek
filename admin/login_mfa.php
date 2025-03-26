<?php

include_once "../utils/function.php";

startSecureSession();

include_once "../utils/regex.php";
include_once "./partials/top.php";

$errors = [];
$successes = [];

/**
 * ******************** [1] Check if submitted form is valid
 */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['secret_code']) && trim($_POST['secret_code']) !== '') {
        $secretCode = trim($_POST['secret_code']);
        if (!preg_match($validPatterns['six_digits_code'], $secretCode)) {
            $errors[] = "Le champ 'Code secret reçu par mail' doit être constitué de 6 chiffres (ex. : 032516).";
        } elseif (!isset($_SESSION['mfa_validation']) || $secretCode !== $_SESSION['mfa_validation']) {
            $errors[] = "Le code secret saisi est incorrect.";
        } else {
            $successes[] = "Authentification validée.";
            unset($_SESSION['mfa_validation']);

            // Redirection vers page d'accueil après succès
            header("Location: home.php");
            exit;
        }
    } else {
        $errors[] = "Le champ 'Code secret reçu par mail' est obligatoire. Merci de saisir une valeur.";
    }
} else {
    $_SESSION = [];
    session_destroy();
    header('Location: ../405.php');
    exit;
}

/**
 * ******************** [2-B] Submitted form is not valid (some errors occurred)
 */

if (count($errors) !== 0) {
    $errorMsg = "<ul>";
    foreach ($errors as $error) {
        $errorMsg .= "<li>$error</li>";
    }
    $errorMsg .= "</ul>";
    echo $errorMsg;
}

include_once "./partials/bottom.php";
