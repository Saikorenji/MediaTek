<?php
include_once "../utils/function.php";
startSecureSession();

// Vérification que l'utilisateur est connecté
if (!isset($_SESSION['is_logged']) || !$_SESSION['is_logged']) {
    header("Location: login_form.php");
    exit;
}

include_once "./partials/top.php";

// On récupère le prénom et nom de session
$firstName = $_SESSION['user']['first_name'] ?? '';
$lastName = $_SESSION['user']['last_name'] ?? '';
?>

<div class="container">
    <h2>Bienvenue <?= htmlspecialchars($firstName . ' ' . $lastName) ?> 👋</h2>
    <p>Vous êtes maintenant connecté à votre espace MediaTek.</p>

    <div class="dashboard-actions">
        <ul>
            <li><a href="book_index.php">📚 Gérer mes livres</a></li>
            <li><a href="illustration_index.php">🖼️ Gérer mes illustrations</a></li>
            <li><a href="profile.php">👤 Voir mon profil</a></li>
            <li><a href="logout.php">🚪 Se déconnecter</a></li>
        </ul>
    </div>
</div>

<?php include_once "./partials/bottom.php"; ?>
