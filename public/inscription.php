<?php
$pageTitle = "Inscription";

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/mailer.php';
include_once __DIR__ . '/../includes/header.php';

$message = null;

$nom = '';
$prenom = '';
$telephone = '';
$email = '';
$adresse = '';
$code_postal = '';
$ville = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $code_postal = trim($_POST['code_postal'] ?? '');
    $ville = trim($_POST['ville'] ?? '');
    $password = $_POST['password'] ?? '';

    if (
        empty($nom) ||
        empty($prenom) ||
        empty($telephone) ||
        empty($email) ||
        empty($adresse) ||
        empty($code_postal) ||
        empty($ville) ||
        empty($password)
    ) {
        $message = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email invalide.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).{10,}$/', $password)) {
        $message = "Le mot de passe doit contenir au moins 10 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
    } else {
        $sql = "SELECT * FROM utilisateur WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $message = "Un compte avec cet email existe déjà. Veuillez choisir un autre email.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO utilisateur 
                    (nom, prenom, telephone, email, adresse, code_postal, ville, mot_de_passe, role_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $nom,
                $prenom,
                $telephone,
                $email,
                $adresse,
                $code_postal,
                $ville,
                $hashedPassword,
                3
            ]);

            // Envoi de l'email de bienvenue
            $sujet = "Bienvenue chez Vite et Gourmand !";

            $lienConnexion = "http://localhost/ecf_vite_et_gourmand/public/connexion.php";

            $messageEmail = "
                <p>Bonjour " . htmlspecialchars($prenom) . ",</p>
                <p>Merci de vous être inscrit sur Vite et Gourmand. Nous sommes ravis de vous compter parmi nos clients !</p>
                <p>
                    Vous pouvez dès à présent vous connecter à votre compte en cliquant 
                    <a href='" . htmlspecialchars($lienConnexion) . "'>ici</a>.
                </p>
                <p>Bon appétit !</p>
            ";

            envoyerEmail($email, $sujet, $messageEmail);

            header('Location: connexion.php');
            exit;
        }
    }
}
?>

<form method="POST">
    <div class="form-container">

        <h2>Inscription</h2>

        <div class="form-inscription">
            <label for="nom">Nom :</label>
            <input 
                type="text" 
                id="nom" 
                name="nom" 
                placeholder="Nom" 
                value="<?= htmlspecialchars($nom) ?>" 
                required
            >
        </div>

        <div class="form-inscription">
            <label for="prenom">Prénom :</label>
            <input 
                type="text" 
                id="prenom" 
                name="prenom" 
                placeholder="Prénom" 
                value="<?= htmlspecialchars($prenom) ?>" 
                required
            >
        </div>

        <div class="form-inscription">
            <label for="telephone">Numéro de GSM :</label>
            <input 
                type="tel" 
                id="telephone" 
                name="telephone" 
                placeholder="Téléphone" 
                value="<?= htmlspecialchars($telephone) ?>" 
                required
            >
        </div>

        <div class="form-inscription">
            <label for="email">Email :</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                placeholder="Email" 
                value="<?= htmlspecialchars($email) ?>" 
                required
            >
        </div>

        <div class="form-inscription">
            <label for="adresse">Adresse postale :</label>
            <input 
                type="text" 
                id="adresse" 
                name="adresse" 
                placeholder="Adresse postale" 
                value="<?= htmlspecialchars($adresse) ?>" 
                required
            >
        </div>

        <div class="form-inscription">
            <label for="code_postal">Code postal :</label>
            <input 
                type="text" 
                id="code_postal" 
                name="code_postal" 
                placeholder="Code postal" 
                value="<?= htmlspecialchars($code_postal) ?>" 
                required
            >
        </div>

        <div class="form-inscription">
            <label for="ville">Ville :</label>
            <input 
                type="text" 
                id="ville" 
                name="ville" 
                placeholder="Ville" 
                value="<?= htmlspecialchars($ville) ?>" 
                required
            >
        </div>

        <div class="form-inscription">
            <label for="password">Mot de passe :</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                minlength="10" 
                required
            >
            <small class="form-help">
                10 caractères minimum, une majuscule, une minuscule, un chiffre et un caractère spécial.
            </small>
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