<?php
include_once "../utils/function.php";
startSecureSession();

include_once "../utils/db_connect.php";
include_once "./partials/top.php";

// Vérification de la session utilisateur
$email = $_SESSION['user']['email'] ?? null;

if (!$email) {
    echo "<div class='alert alert-danger'>Impossible de récupérer les informations utilisateur.</div>";
    include_once "./partials/bottom.php";
    exit;
}

// Récupération des infos utilisateur
try {
    $query = "SELECT id, first_name, last_name, email, birth_date, created_at, updated_at FROM user WHERE email = :email LIMIT 1";
    $statement = $pdo->prepare($query);
    $statement->execute([':email' => $email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "<div class='alert alert-warning'>Utilisateur non trouvé dans la base de données.</div>";
        include_once "./partials/bottom.php";
        exit;
    }
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Erreur : " . $e->getMessage() . "</div>";
    include_once "./partials/bottom.php";
    exit;
}
?>

<div class="title-space-between">
    <h4>Profil de <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h4>
</div>

<table class="striped">
    <tbody>
        <tr>
            <th>Nom :</th>
            <td><?= htmlspecialchars($user['last_name']) ?></td>
        </tr>
        <tr>
            <th>Prénom :</th>
            <td><?= htmlspecialchars($user['first_name']) ?></td>
        </tr>
        <tr>
            <th>Email :</th>
            <td><?= htmlspecialchars($user['email']) ?></td>
        </tr>
        <tr>
            <th>Date de naissance :</th>
            <td><?= date('d/m/Y', strtotime($user['birth_date'])) ?></td>
        </tr>
        <tr>
            <th>Inscrit le :</th>
            <td><?= date('d/m/Y à H:i', strtotime($user['created_at'])) ?></td>
        </tr>
        <tr>
            <th>Dernière modification :</th>
            <td><?= date('d/m/Y à H:i', strtotime($user['updated_at'])) ?></td>
        </tr>
    </tbody>
</table>

<?php include_once "./partials/bottom.php"; ?>
