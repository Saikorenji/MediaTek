<?php
include_once "../utils/function.php";
include_once "../utils/regex.php";

startSecureSession();

include_once "./partials/top.php";

$errors = [];
$successes = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || !preg_match($validPatterns['email'], $email)) {
        $errors[] = "Email invalide.";
    }

    if ($password === '') {
        $errors[] = "Mot de passe requis.";
    }

    if (count($errors) === 0) {
        include_once "../utils/db_connect.php";

        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['is_logged'] = true;
            $_SESSION['user'] = [
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email' => $user['email']
            ];

            // Génère un code MFA
            $code = random_int(100000, 999999);
            $_SESSION['mfa_validation'] = $code;

            // Simule un envoi de mail
            fakeMailSend("{$user['first_name']} {$user['last_name']}", "Code MFA", $code);

            $successes[] = "Authentification réussie.";
        } else {
            $errors[] = "Identifiants incorrects.";
        }
    }
} else {
    header('Location: ../405.php');
    exit;
}

if (count($errors) > 0) {
    echo "<ul>";
    foreach ($errors as $e) echo "<li>$e</li>";
    echo "</ul>";
} else {
    echo "<ul>";
    foreach ($successes as $s) echo "<li>$s</li>";
    echo "</ul>";
    ?>
    <div class="form-container">
        <h4>Code secret - MFA</h4>
        <form class="login-form" action="login_mfa.php" method="post">
            <div class="form-block">
                <label for="secret_code">Code secret reçu par mail</label>
                <input type="number" id="secret-code" name="secret_code" required>
            </div>
            <input type="submit" value="Confirmer le code secret">
        </form>
    </div>
    <?php
}

include_once "./partials/bottom.php";
?>
