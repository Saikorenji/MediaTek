<?php
include_once "../utils/function.php";
startSecureSession();

// Redirection si l'utilisateur n'est pas connecté
if (empty($_SESSION['is_logged']) || !isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user']; // first_name, last_name, email
include_once "./partials/top.php";
?>

<div class="title-space-between">
    <h2>Bienvenue <?= htmlspecialchars($user['first_name']) ?> <?= htmlspecialchars($user['last_name']) ?> !</h2>
    <a href="logout.php" class="btn btn-danger">Se déconnecter</a>
</div>

<div class="box">
    <p>Bienvenue sur votre tableau de bord personnalisé.</p>
    <p>Vous avez maintenant accès à toutes les fonctionnalités de la plateforme.</p>
    <ul>
        <li>📚 Gérer vos livres et illustrations</li>
        <li>👤 Voir ou modifier votre profil</li>
        <li>⚙️ Accéder à vos préférences</li>
    </ul>
    <p><a href="profile.php" class="btn btn-primary">Voir mon profil</a></p>
</div>

<?php include_once "./partials/bottom.php"; ?>
