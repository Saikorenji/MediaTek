<?php
include_once "./partials/top.php";
include_once "../utils/function.php";

startSecureSession();

if (!isset($_SESSION['is_logged']) || !$_SESSION['is_logged']) {
    header('Location: login.php');
    exit;
}

$firstName = $_SESSION['user']['first_name'] ?? 'Utilisateur';
$lastName = $_SESSION['user']['last_name'] ?? '';
?>

<div class="title-space-between">
    <h2>Bienvenue, <?= htmlspecialchars($firstName . ' ' . $lastName) ?> !</h2>
</div>

<div>
    <p>Vous êtes bien connecté à votre espace personnel.</p>
    <ul>
        <li><a href="book_index.php">Voir les livres disponibles</a></li>
        <li><a href="illustration_index.php">Voir les illustrations</a></li>
        <li><a href="profile.php">Mon profil</a></li>
        <li><a href="logout.php">Se déconnecter</a></li>
    </ul>
</div>

<?php include_once "./partials/bottom.php"; ?>
