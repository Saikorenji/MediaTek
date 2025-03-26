<?php
include_once "../utils/function.php";
startSecureSession();

if (!isset($_SESSION['is_logged']) || $_SESSION['is_logged'] !== true) {
    header("Location: login.php");
    exit;
}


include_once "../utils/db_connect.php";
include_once "../utils/regex.php";
include_once "./partials/top.php";

$errors = [];
$successes = [];

// Vérification que l'utilisateur est connecté
if (!isset($_SESSION['user']['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['user']['email'];
$pdo = getPDO();

// Charger les données actuelles de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
$stmt->execute([':email' => $email]);
$user = $stmt->fetch();

if (!$user) {
    echo "<p class='alert alert-danger'>Utilisateur introuvable.</p>";
    include_once "./partials/bottom.php";
    exit;
}

// Si formulaire soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $birthDate = trim($_POST['birth_date']);
    $newEmail = trim($_POST['email']);

    // Validation
    if (!preg_match($validPatterns['proper_name'], $firstName)) {
        $errors[] = "Le prénom n'est pas valide.";
    }
    if (!preg_match($validPatterns['proper_name'], $lastName)) {
        $errors[] = "Le nom n'est pas valide.";
    }
    if (!preg_match($validPatterns['date_hyphens_fr'], $birthDate)) {
        $errors[] = "La date de naissance doit être au format JJ-MM-AAAA.";
    }
    if (!preg_match($validPatterns['email'], $newEmail)) {
        $errors[] = "L'email est invalide.";
    }

    if (empty($errors)) {
        // Transformer date vers format MySQL
        list($d, $m, $y) = explode("-", $birthDate);
        $birthDateSql = "$y-$m-$d";

        $stmt = $pdo->prepare("UPDATE user SET first_name = :first, last_name = :last, birth_date = :birth, email = :email WHERE id = :id");
        $stmt->execute([
            ':first' => $firstName,
            ':last' => $lastName,
            ':birth' => $birthDateSql,
            ':email' => $newEmail,
            ':id' => $user['id']
        ]);

        $successes[] = "Profil mis à jour avec succès.";
        $_SESSION['user']['email'] = $newEmail;
        $_SESSION['user']['first_name'] = $firstName;
        $_SESSION['user']['last_name'] = $lastName;

        // Rafraîchir $user
        $user['first_name'] = $firstName;
        $user['last_name'] = $lastName;
        $user['birth_date'] = $birthDateSql;
        $user['email'] = $newEmail;
    }
}
?>

<div class="container mt-4">
    <h2>Modifier mon profil</h2>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <ul><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul>
        </div>
    <?php endif; ?>

    <?php if ($successes): ?>
        <div class="alert alert-success">
            <ul><?php foreach ($successes as $s) echo "<li>$s</li>"; ?></ul>
        </div>
    <?php endif; ?>

    <form method="POST" class="mt-3">
        <div class="form-group">
            <label>Prénom :</label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Nom :</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Date de naissance (JJ-MM-AAAA) :</label>
            <?php
            $birthFr = date('d-m-Y', strtotime($user['birth_date']));
            ?>
            <input type="text" name="birth_date" value="<?= $birthFr ?>" required>
        </div>
        <div class="form-group">
            <label>Email :</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <button type="submit" class="btn btn-success mt-3">Enregistrer les modifications</button>
        <a href="profile.php" class="btn btn-secondary mt-3">Annuler</a>
    </form>
</div>

<?php include_once "./partials/bottom.php"; ?>
