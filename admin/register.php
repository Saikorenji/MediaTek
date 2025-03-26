<?php

include_once "./partials/top.php";
include_once "../utils/regex.php";
include_once "../utils/db_connect.php"; // Connexion PDO

$errors = [];
$successes = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstName = trim(filter_input(INPUT_POST, 'first_name'));
    $lastName = trim(filter_input(INPUT_POST, 'last_name'));
    $birthDate = trim(filter_input(INPUT_POST, 'birth_date'));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
    $password = trim(filter_input(INPUT_POST, 'password'));
    $passwordConfirm = trim(filter_input(INPUT_POST, 'password_confirm'));

    // [1] Validation
    if (!$lastName || !preg_match($validPatterns['proper_name'], $lastName)) {
        $errors[] = "Nom invalide ou manquant.";
    }

    if (!$firstName || !preg_match($validPatterns['proper_name'], $firstName)) {
        $errors[] = "Prénom invalide ou manquant.";
    }

    if (!$birthDate || !preg_match($validPatterns['date_hyphens_fr'], $birthDate)) {
        $errors[] = "Date de naissance invalide (format attendu : JJ-MM-AAAA).";
    }

    if (!$email) {
        $errors[] = "Email invalide.";
    } else {
        // Vérification unicité de l'email
        $stmt = $pdo->prepare("SELECT id FROM user WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Cet email est déjà utilisé.";
        }
    }

    if (!$password || !preg_match($validPatterns['password_policy'], $password)) {
        $errors[] = "Mot de passe invalide.";
    } elseif ($password !== $passwordConfirm) {
        $errors[] = "La confirmation ne correspond pas au mot de passe.";
    }

    // [2] Insertion si pas d'erreurs
    if (count($errors) === 0) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        list($day, $month, $year) = explode("-", $birthDate);
        $mysqlBirthDate = "$year-$month-$day";

        $now = (new DateTime())->format('Y-m-d H:i:s');

        $stmt = $pdo->prepare("INSERT INTO user (last_name, first_name, birth_date, email, password, created_at, updated_at)
                               VALUES (?, ?, ?, ?, ?, ?, ?)");

        if ($stmt->execute([$lastName, $firstName, $mysqlBirthDate, $email, $hashedPassword, $now, $now])) {
            $successes[] = "Votre compte a bien été créé.";
        } else {
            $errors[] = "Une erreur est survenue lors de la création du compte.";
        }
    }
} else {
    header('Location: ../405.php');
    exit;
}

// [3] Affichage messages
if (!empty($errors)) {
    echo "<div class='alert alert-danger'><ul>";
    foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>";
    echo "</ul></div>";
}
if (!empty($successes)) {
    echo "<div class='alert alert-success'><ul>";
    foreach ($successes as $s) echo "<li>" . htmlspecialchars($s) . "</li>";
    echo "</ul></div>";
}

include_once "./partials/bottom.php";
