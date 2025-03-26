<?php
include_once "../utils/function.php";
startSecureSession();

if (!isset($_SESSION['is_logged']) || $_SESSION['is_logged'] !== true) {
    header("Location: login.php");
    exit;
}

include_once "../utils/db_connect.php";
include_once "./partials/top.php";

$errors = [];
$userData = [];

if (!isset($_SESSION['user']['email'])) {
    echo "<p class='alert alert-danger'>Impossible de récupérer les informations utilisateur.</p>";
} else {
    try {
        $pdo = getPDO();
        $query = "SELECT * FROM user WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':email' => $_SESSION['user']['email']]);
        $userData = $stmt->fetch();

        if (!$userData) {
            echo "<p class='alert alert-danger'>Utilisateur non trouvé.</p>";
        } else {
            ?>
            <div class="container mt-4">
                <h2>Profil de <?= htmlspecialchars($userData['first_name']) ?> <?= htmlspecialchars($userData['last_name']) ?></h2>
                <ul class="list-group mt-3">
                    <li class="list-group-item"><strong>Nom :</strong> <?= htmlspecialchars($userData['last_name']) ?></li>
                    <li class="list-group-item"><strong>Prénom :</strong> <?= htmlspecialchars($userData['first_name']) ?></li>
                    <li class="list-group-item"><strong>Email :</strong> <?= htmlspecialchars($userData['email']) ?></li>
                    <li class="list-group-item"><strong>Date de naissance :</strong> <?= htmlspecialchars($userData['birth_date']) ?></li>
                    <li class="list-group-item"><strong>Inscrit le :</strong> <?= htmlspecialchars($userData['created_at']) ?></li>
                </ul>

                <!-- ✅ Bouton Modifier mon profil -->
                <div class="mt-3">
                    <a href="profile_edit.php" class="btn btn-primary">
                        <i class="light-icon-pencil"></i> Modifier mon profil
                    </a>
                </div>
            </div>
            <?php
        }
    } catch (PDOException $e) {
        echo "<p class='alert alert-danger'>Erreur lors de la récupération du profil : " . $e->getMessage() . "</p>";
    }
}

include_once "./partials/bottom.php";
?>
