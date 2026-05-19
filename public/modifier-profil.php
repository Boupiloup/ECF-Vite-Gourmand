<?php
$pageTitle = "Modifier mon profil";

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];
$message = null;
$erreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $code_postal = trim($_POST['code_postal'] ?? '');
    $ville = trim($_POST['ville'] ?? '');

    if (empty($nom) || empty($prenom) || empty($email)) {
        $erreur = "Le nom, le prénom et l'email sont obligatoires.";
    } else {
        $sqlUpdate = "UPDATE utilisateur
                      SET nom = ?, prenom = ?, email = ?, telephone = ?, adresse = ?, code_postal = ?, ville = ?
                      WHERE id = ?";

        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([
            $nom,
            $prenom,
            $email,
            $telephone,
            $adresse,
            $code_postal,
            $ville,
            $utilisateur_id
        ]);

        $message = "Votre profil a bien été mis à jour.";
    }
}

$sql = "SELECT nom, prenom, email, telephone, adresse, code_postal, ville
        FROM utilisateur
        WHERE id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$utilisateur_id]);

$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    header('Location: connexion.php');
    exit;
}
?>

<main class="modifier-profil-page">

    <section class="modifier-profil-container">

        <h1 class="modifier-profil-title">Modifier mon profil</h1>

        <?php if ($message): ?>
            <p class="success-message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php if ($erreur): ?>
            <p class="error-message"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>

        <form action="modifier-profil.php" method="post" class="modifier-profil-form">

            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>" required>
            </div>

            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($utilisateur['prenom']) ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($utilisateur['email']) ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="text" id="telephone" name="telephone"
                    value="<?= htmlspecialchars($utilisateur['telephone']) ?>">
            </div>

            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" id="adresse" name="adresse" value="<?= htmlspecialchars($utilisateur['adresse']) ?>">
            </div>

            <div class="form-group">
                <label for="code_postal">Code postal</label>
                <input type="text" id="code_postal" name="code_postal"
                    value="<?= htmlspecialchars($utilisateur['code_postal']) ?>">
            </div>

            <div class="form-group">
                <label for="ville">Ville</label>
                <input type="text" id="ville" name="ville" value="<?= htmlspecialchars($utilisateur['ville']) ?>">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit-profil">
                    Enregistrer les modifications
                </button>

                <a href="user-dashboard.php" class="btn-retour-dashboard">
                    Retour à mon espace
                </a>
            </div>

        </form>

    </section>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>