<?php
session_start();
$pageTitle = "Connexion";
require_once '../includes/db.php';
include_once __DIR__ . '/../includes/header.php';

$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $sql = "SELECT * FROM utilisateur WHERE email = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['utilisateur_id'] = $user['id'];
        $_SESSION['utilisateur_email'] = $user['email'];
        $_SESSION['utilisateur_role'] = $user['role_id'];

        header('location: index.php');
        exit;

    } else {
        $message = "Email ou mot de passe incorrect";
    }
}
?>

<form method="POST" action="">
    <div class="form-container">
        <h2>Connexion</h2>
        <div class="form-connexion">
            <label for="email">Email:</label>
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="form-connexion">
            <label for="password">Mot de passe:</label>
            <input type="password" name="password" placeholder="Mot de passe" required>
        </div>
    </div>

    <?php if ($message): ?>
        <p class="error-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <button type="submit">Se connecter</button>

<div class="form-footer">
    <p>Pas encore de compte ?</p>
    <a href="inscription.php" class="link-register">Créer un compte</a>
</div>

</form>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>