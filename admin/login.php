<?php
include_once "../utils/function.php";
include_once "../utils/regex.php";
include_once "../utils/db_connect.php";

startSecureSession();
include_once "./partials/top.php";

$errors = [];
$successes = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!preg_match($validPatterns['email'], $email)) {
        $errors[] = "Le champ 'Email' est requis et doit être valide.";
    }

    if (empty($password)) {
        $errors[] = "Le champ 'Mot de passe' est requis.";
    }

    if (empty($errors)) {
        try {
            $pdo = getPDO();

            $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Auth OK → stocker user en session
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name']
                ];

                $sixDigitsCode = digitsCode(); // génère un code MFA
                $_SESSION['mfa_validation'] = $sixDigitsCode;

                fakeMailSend($user['first_name'] . " " . $user['last_name'], "Code MFA", $sixDigitsCode);

                $successes[] = "Authentification réussie.";
            } else {
                $errors[] = "Email ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur SQL : " . $e->getMessage();
        }
    }
} else {
    header('Location: ../405.php');
    exit;
}

// Affichage des messages
if (!empty($errors)) {
    echo "<ul class='alert alert-danger'>";
    foreach ($errors as $error) echo "<li>$error</li>";
    echo "</ul>";
} elseif (!empty($successes)) {
    echo "<ul class='alert alert-success'>";
    foreach ($successes as $success) echo "<li>$success</li>";
    echo "</ul>";
    ?>
    <div class="form-container">
        <h4>Code secret - MFA</h4>
        <form class="login-form" action="login_mfa.php" method="post" novalidate="">
            <div class="form-block">
                <label for="secret_code">Code secret reçu par mail</label>
                <input type="number" id="secret-code" name="secret_code" placeholder="Votre code secret à 6 chiffres" required="">
            </div>
            <p><a href="login.php"><i class="light-icon-refresh"></i> Recevoir un nouveau code secret ?</a></p>
            <input type="submit" name="login_mfa_submit" value="Confirmer le code secret">
        </form>
    </div>
    <?php
}

include_once "./partials/bottom.php";
?>
