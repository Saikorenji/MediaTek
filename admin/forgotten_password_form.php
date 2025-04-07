<?php
include_once "../utils/function.php";
include_once "../utils/regex.php";
include_once "../utils/db_connect.php";
include_once "./partials/top.php";

startSecureSession();

$errors = [];
$successes = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email) || !preg_match($validPatterns['email'], $email)) {
        $errors[] = "Veuillez entrer une adresse email valide.";
    } else {
        try {
            $pdo = getPDO();

            $stmt = $pdo->prepare("SELECT id, first_name, last_name FROM user WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            if ($user) {
                // Génère un code ou lien de réinitialisation
                $resetCode = random_int(100000, 999999);
                $_SESSION['password_reset_code'] = $resetCode;
                $_SESSION['password_reset_email'] = $email;
                $_SESSION['password_reset_expires'] = time() + 900; // valable 15 minutes

                $fullname = $user['first_name'] . ' ' . $user['last_name'];
                fakeMailSend($fullname, "Code de réinitialisation", "Voici votre code de réinitialisation : $resetCode");

                $successes[] = "Un code de réinitialisation a été envoyé à votre adresse email.";
            } else {
                $errors[] = "Aucun utilisateur trouvé avec cette adresse email.";
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de la recherche : " . $e->getMessage();
        }
    }
}
?>

<div class="container mt-5">
    <h2>Mot de passe oublié ?</h2>
    <p>Veuillez entrer votre adresse email. Un code de réinitialisation vous sera envoyé.</p>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul><?php foreach ($errors as $err) echo "<li>$err</li>"; ?></ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($successes)): ?>
        <div class="alert alert-success">
            <ul><?php foreach ($successes as $msg) echo "<li>$msg</li>"; ?></ul>
        </div>
        <a href="reset_password_form.php" class="btn btn-primary mt-3">Réinitialiser mon mot de passe</a>
    <?php else: ?>
        <form action="" method="post" class="mt-3" novalidate>
            <div class="form-group mb-3">
                <label for="email">Adresse email</label>
                <input type="email" name="email" id="email" class="form-control" required placeholder="john.doe@email.com">
            </div>
            <button type="submit" class="btn btn-primary">Envoyer le code</button>
        </form>
    <?php endif; ?>
</div>

<?php include_once "./partials/bottom.php"; ?>
