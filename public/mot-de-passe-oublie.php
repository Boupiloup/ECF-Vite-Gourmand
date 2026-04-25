<?php
session_start();
$pageTitle = "Mot de passe oublié";
require_once '../includes/db.php';
include_once __DIR__ . '/../includes/header.php';

$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $message = "Veuillez entrer votre adresse email.";
    } else {
        $sql = "SELECT * FROM utilisateur WHERE email = ? ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $token = bin2hex(random_bytes(32)); // Génère un token de 64 caractères
            $expire = date('Y-m-d H:i:s', strtotime('+15 minutes')); // Le token expire dans 15 minutes

            // Modifie l'utilisateur concerné :
            // UPDATE = table à modifier
            // SET = colonnes à mettre à jour
            // WHERE = ligne ciblée grâce à l'id utilisateur
            $sql = "UPDATE utilisateur
            SET reset_token = ?, reset_token_expire = ?
            WHERE id = ?";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$token, $expire, $user['id']]);

            $lienReset = "reset-password-form.php?token=" . $token;
        }

        $message = "Si un compte existe avec cet email, un lien de réinitialisation a été envoyé.";
    }

}
?>

<main>
    <div class="form-container">

        <h2>Mot de passe oublié</h2>

        <p>
            Veuillez indiquer votre adresse email pour recevoir un lien de réinitialisation de votre mot de passe.
        </p>

        <form method="POST">

            <div class="form-connexion">
                <label for="email">Email :</label>
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <button type="submit">Réinitialiser le mot de passe</button>

        </form>

        <?php if ($message): ?>
            <p class="error-message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

    </div>
</main>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>