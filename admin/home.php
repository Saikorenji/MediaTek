<?php
include_once "../utils/function.php";
startSecureSession();

// Rediriger si l'utilisateur n'est pas connecté
if (!isset($_SESSION['is_logged']) || $_SESSION['is_logged'] !== true || !isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$firstName = htmlspecialchars($user['first_name']);
$lastName = htmlspecialchars($user['last_name']);
$email = htmlspecialchars($user['email']);

include_once "./partials/top.php";
?>

<div class="title-space-between">
    <h4>Bienvenue <?= $firstName . ' ' . $lastName ?> !</h4>
    <p>Adresse email : <strong><?= $email ?></strong></p>
</div>

<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        🧑 Profil de l'utilisateur
    </div>
    <div class="card-body">
        <p><strong>Nom :</strong> <?= $lastName ?></p>
        <p><strong>Prénom :</strong> <?= $firstName ?></p>
        <p><strong>Email :</strong> <?= $email ?></p>
    </div>
</div>

<div style="margin-top: 2rem;">
    <a class="btn btn-danger" href="logout.php">Se déconnecter</a>
</div>

<?php
include_once "./partials/bottom.php";
?>
