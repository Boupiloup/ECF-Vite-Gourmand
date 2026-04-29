<?php
session_start();

$pageTitle = "Mot de passe oublié";

// Connexion à la base de données via PDO
require_once '../includes/db.php';

// Inclusion de la fonction d'envoi d'email avec PHPMailer
require_once '../includes/mailer.php';

// Inclusion du header commun
include_once __DIR__ . '/../includes/header.php';

// Variable utilisée pour afficher un message à l'utilisateur
$message = null;

// Vérifie si le formulaire a été envoyé
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupère l'email envoyé par le formulaire
    // trim() supprime les espaces inutiles au début et à la fin
    // ?? '' évite une erreur si le champ email n'existe pas
    $email = trim($_POST['email'] ?? '');

    // Vérifie que le champ email n'est pas vide
    if (empty($email)) {
        $message = "Veuillez entrer votre adresse email.";
    } else {

        // Recherche un utilisateur correspondant à l'email saisi
        // Le ? est remplacé ensuite par la valeur de $email dans execute()
        $sql = "SELECT * FROM utilisateur WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        // Récupère l'utilisateur trouvé sous forme de tableau associatif
        // Si aucun utilisateur n'est trouvé, $user vaudra false
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si un utilisateur existe avec cet email
        if ($user) {

            // Génère un token sécurisé de réinitialisation
            // random_bytes(32) génère 32 octets aléatoires
            // bin2hex() transforme ces octets en chaîne lisible de 64 caractères
            $token = bin2hex(random_bytes(32));

            // Définit une date d'expiration du token à 15 minutes
            $expire = date('Y-m-d H:i:s', strtotime('+15 minutes'));

            // Enregistre le token et sa date d'expiration dans la base de données
            // Cela permettra ensuite de vérifier le lien dans reset-password-form.php
            $sql = "UPDATE utilisateur
                    SET reset_token = ?, reset_token_expire = ?
                    WHERE id = ?";

            $stmt = $pdo->prepare($sql);

            // Les valeurs remplacent les ? dans l'ordre :
            // 1er ? = $token
            // 2e ? = $expire
            // 3e ? = $user['id']
            $stmt->execute([$token, $expire, $user['id']]);

            // Crée le lien de réinitialisation contenant le token
            // Ce lien sera envoyé par email à l'utilisateur
            $lienReset = "http://localhost/ecf_vite_et_gourmand/public/reset-password-form.php?token=" . $token;

            // Prépare le contenu de l'email
            $sujet = "Réinitialisation de votre mot de passe";

            $contenuEmail = "
            <h1>Bonjour " . htmlspecialchars($user['prenom']) . ",</h1>
            <p>Vous avez demandé à réinitialiser votre mot de passe. Cliquez sur le lien ci-dessous pour choisir un nouveau mot de passe :</p>
            <a href='" . htmlspecialchars($lienReset) . "'>Réinitialiser mon mot de passe</a>
            <p>Ce lien est valable pendant 15 minutes. Si vous n'avez pas demandé cette réinitialisation, vous pouvez ignorer cet email.</p>
            ";

            // Envoie l'email à l'utilisateur
            envoyerEmail($email, $sujet, $contenuEmail);
        }

        // Message volontairement identique, que l'email existe ou non
        // Cela évite de révéler si une adresse email est inscrite dans la base
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

        <!-- Formulaire d'envoi de l'email -->
        <form method="POST">

            <div class="form-connexion">
                <label for="email">Email :</label>

                <!-- Champ email obligatoire -->
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <button type="submit">Réinitialiser le mot de passe</button>

        </form>

        <!-- Affichage du message utilisateur -->
        <?php if ($message): ?>
            <p class="error-message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

    </div>
</main>

<?php
// Inclusion du footer commun
include_once __DIR__ . '/../includes/footer.php';
?>