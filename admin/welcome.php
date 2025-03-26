<?php
include_once "../utils/auth_check.php"; // Redirige si l'utilisateur n'est pas connecté
include_once "./partials/top.php";

// On récupère les infos de l'utilisateur connecté
$firstName = $_SESSION['user']['first_name'] ?? 'Utilisateur';
$lastName = $_SESSION['user']['last_name'] ?? '';
?>

<div class="container mt-5">
    <h1 class="mb-4">Bienvenue, <?= htmlspecialchars($firstName . ' ' . $lastName) ?> 👋</h1>

    <div class="alert alert-info">
        Vous êtes connecté à votre compte personnel MediaTek.
    </div>

    <div class="mt-4">
        <a href="dashboard.php" class="btn btn-secondary me-2">
            <i class="light-icon-dashboard"></i> Dashboard
        </a>

        <a href="profile_edit.php" class="btn btn-primary me-2">
            <i class="light-icon-pencil"></i> Modifier mon profil
        </a>

        <a href="logout.php" class="btn btn-danger">
            <i class="light-icon-logout"></i> Se déconnecter
        </a>
    </div>
</div>

<?php include_once "./partials/bottom.php"; ?>
