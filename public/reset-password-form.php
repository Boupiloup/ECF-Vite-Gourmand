<?php
session_start();

$pageTitle = "Réinitialisation du mot de passe";

require_once '../includes/db.php';
include_once __DIR__ . '/../includes/header.php';

$message = null;
$erreurLien = null;
$erreurFormulaire = null;

$token = $_POST['token'] ?? $_GET['token'] ?? null;

if (empty($token)) {
    $erreurLien = "Lien de réinitialisation invalide.";
} else {
    $sql = "SELECT * FROM utilisateur WHERE reset_token = ? AND reset_token_expire > NOW()";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $erreurLien = "Lien de réinitialisation invalide ou expiré.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$erreurLien) {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($password) || empty($confirm_password)) {
        $erreurFormulaire = "Veuillez remplir tous les champs.";

    } elseif ($password !== $confirm_password) {
        $erreurFormulaire = "Les mots de passe ne correspondent pas.";

    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).{10,}$/', $password)) {
        $erreurFormulaire = "Le mot de passe doit contenir au moins 10 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";

    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE utilisateur 
                SET mot_de_passe = ?, reset_token = NULL, reset_token_expire = NULL 
                WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$hashedPassword, $user['id']]);

        $message = "Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.";
    }
}
?>

<main>
    <div class="form-container">
        <h2>Réinitialisation du mot de passe</h2>

        <?php if ($erreurLien): ?>

            <p class="error"><?= htmlspecialchars($erreurLien) ?></p>

        <?php elseif ($message): ?>

            <p class="success"><?= htmlspecialchars($message) ?></p>
            <a href="connexion.php">Se connecter</a>

        <?php else: ?>

            <?php if ($erreurFormulaire): ?>
                <p class="error"><?= htmlspecialchars($erreurFormulaire) ?></p>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <div class="form-connexion">
                    <label for="password">Nouveau mot de passe :</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-connexion">
                    <label for="confirm_password">Confirmer le mot de passe :</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <button type="submit">Réinitialiser le mot de passe</button>
            </form>

        <?php endif; ?>
    </div>
</main>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>