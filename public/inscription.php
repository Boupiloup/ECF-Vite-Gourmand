<?php
session_start();
$pageTitle = "Inscription";
require_once '../includes/db.php';
include_once __DIR__ . '/../includes/header.php';

$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';


    if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        $message = "Tous les champs sont obligatoires.";
    } else {
        $sql = "SELECT * FROM utilisateur WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $message = "Un compte avec cet email existe déjà. Veuillez choisir un autre email.";
        }
    }
}
?>

<form method="POST">
    <div class="form-container">

        <h2>Inscription</h2>

        <div class="form-inscription">
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" placeholder="Nom" required>
        </div>
        <div class="form-inscription">
            <label for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="prenom" placeholder="Prénom" required>
        </div>
        <div class="form-inscription">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Email" required>
        </div>
        <div class="form-inscription">
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" placeholder="Mot de passe" required>
        </div>


        <?php if ($message): ?>
            <p class="error-message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <button type="submit">S'inscrire</button>

        <div class="form-footer">
            <p>Vous avez déjà un compte ?</p>
            <a href="connexion.php" class="link-register">Se connecter</a>
        </div>
    </div>
</form>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>