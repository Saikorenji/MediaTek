<?php
include_once "../utils/function.php";
startSecureSession();

if (!isset($_SESSION['is_logged']) || $_SESSION['is_logged'] !== true || !isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

include_once "../utils/db_connect.php";
include_once "./partials/top.php";

$pdo = getPDO();
$user = $_SESSION['user'];
$success = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);

    if (empty($firstName) || strlen($firstName) > 50) {
        $errors[] = "Le champ Prénom est requis et doit contenir moins de 50 caractères.";
    }
    if (empty($lastName) || strlen($lastName) > 50) {
        $errors[] = "Le champ Nom est requis et doit contenir moins de 50 caractères.";
    }

    if (empty($errors)) {
        $query = "UPDATE user SET first_name = :first_name, last_name = :last_name, updated_at = NOW() WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $success = $stmt->execute([
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':email' => $user['email']
        ]);

        if ($success) {
            $_SESSION['user']['first_name'] = $firstName;
            $_SESSION['user']['last_name'] = $lastName;
            $success = "Profil mis à jour avec succès.";
        } else {
            $errors[] = "Une erreur est survenue lors de la mise à jour.";
        }
    }
}
?>

<div class="container mt-4">
    <h2>Modifier mon profil</h2>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"> <?= $success ?> </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="first_name" class="form-label">Prénom</label>
            <input type="text" name="first_name" id="first_name" class="form-control" value="<?= htmlspecialchars($_SESSION['user']['first_name']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Nom</label>
            <input type="text" name="last_name" id="last_name" class="form-control" value="<?= htmlspecialchars($_SESSION['user']['last_name']) ?>" required>
        </div>

        <button type="submit" class="btn btn-success">Mettre à jour</button>
        <a href="welcome.php" class="btn btn-secondary">Retour</a>
    </form>
</div>

<?php include_once "./partials/bottom.php"; ?>
