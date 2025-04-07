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
    <div class="text-center mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-4 mt-5">
    <a class="btn btn-dark shadow-sm px-4 py-2" href="index.php">
        <i class="light-icon-home me-2"></i> Accès au Dashboard
    </a>
    <a class="btn btn-primary shadow-sm px-4 py-2" href="profile_edit.php">
        <i class="light-icon-pencil me-2"></i> Modifier mon profil
    </a>
    <a class="btn btn-danger shadow-sm px-4 py-2" href="logout.php">
        <i class="light-icon-logout me-2"></i> Se déconnecter
    </a>
</div>
</div>
</div>

<?php include_once "./partials/bottom.php"; ?>
