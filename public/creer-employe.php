<?php
session_start();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/mailer.php';

$pageTitle = 'Créer un employé';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$role = (int) $_SESSION['utilisateur_role'];

if ($role !== 1) {
    header('Location: connexion.php');
    exit();
}

$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $password = ($_POST['password'] ?? '');

    if (empty($nom) || empty($prenom) || empty($email) || empty($telephone) || empty($password)) {
        $message = "Veuillez remplir tous les champs du formulaire.";
    } else {
        $sql = "SELECT id FROM utilisateur WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $userExiste = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userExiste) {
            $message = "Cet email est déjà utilisé par un autre compte.";
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO utilisateur 
        (nom, prenom, email, telephone, mot_de_passe, role_id, actif) 
        VALUES (?, ?, ?, ?, ?, 2, 1)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $nom,
                $prenom,
                $email,
                $telephone,
                $passwordHash
            ]);

            $sujet = "Votre compte employé Vite & Gourmand a été créé";

            $messageMail = "
            <h2>Bienvenue chez Vite & Gourmand</h2>
            <p>Un compte employé a été créé pour vous.</p>
            <p>Identifiant : {$email}</p>
            <p>Veuillez contacter votre administrateur pour obtenir votre mot de passe.</p>";

            envoyerEmail($email, $sujet, $messageMail);

            header('Location: gestion-employes.php');
            exit();
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<main class="creer-employe">
    <h1>Créer un compte employé</h1>
    <?php if ($message): ?>
        <p class="error-message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST">
        <div>
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        <div>
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" required>
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="telephone">Téléphone</label>
            <input type="text" id="telephone" name="telephone" required>
        </div>
        <div>
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Créer le compte</button>
    </form>

    <a href="gestion-employes.php">Retour à la gestion des employés</a>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>