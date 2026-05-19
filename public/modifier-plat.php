<?php
session_start();

require_once __DIR__ . '/../includes/db.php';

$pageTitle = 'Modifier un plat';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$role = (int) $_SESSION['utilisateur_role'];

if ($role !== 2 && $role !== 1) {
    header('Location: connexion.php');
    exit();
}

$platId = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

if ($platId <= 0) {
    header('Location: gestion-plats.php');
    exit();
}

$sql = "SELECT * FROM plat WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$platId]);
$plat = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$plat) {
    header('Location: gestion-plats.php');
    exit();
}

$messageErreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $typePlat = $_POST['type_plat'] ?? '';
    $actif = isset($_POST['actif']) ? 1 : 0;

    if (
        empty($nom) ||
        empty($description) ||
        !in_array($typePlat, ['entrée', 'plat', 'dessert'])
    ) {
        $messageErreur = 'Veuillez remplir correctement tous les champs.';
    } else {
        $sql = "
            UPDATE plat
            SET
                nom = ?,
                description = ?,
                type_plat = ?,
                actif = ?
            WHERE id = ?
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $nom,
            $description,
            $typePlat,
            $actif,
            $platId
        ]);

        header('Location: gestion-plats.php');
        exit();
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<main class="page-modifier-plat">

    <h1>Modifier un plat</h1>

    <?php if (!empty($messageErreur)) : ?>
        <p class="message-erreur-plat"><?= htmlspecialchars($messageErreur) ?></p>
    <?php endif; ?>

    <form method="POST" class="formulaire-plat">

        <input type="hidden" name="id" value="<?= htmlspecialchars($plat['id']) ?>">

        <label for="nom">Nom du plat</label>
        <input
            type="text"
            id="nom"
            name="nom"
            value="<?= htmlspecialchars($plat['nom']) ?>"
            required
        >

        <label for="description">Description</label>
        <textarea
            id="description"
            name="description"
            required
        ><?= htmlspecialchars($plat['description']) ?></textarea>

        <label for="type_plat">Type de plat</label>
        <select name="type_plat" id="type_plat" required>
            <option value="">-- Choisir un type --</option>

            <option value="entrée" <?= $plat['type_plat'] === 'entrée' ? 'selected' : '' ?>>
                Entrée
            </option>

            <option value="plat" <?= $plat['type_plat'] === 'plat' ? 'selected' : '' ?>>
                Plat
            </option>

            <option value="dessert" <?= $plat['type_plat'] === 'dessert' ? 'selected' : '' ?>>
                Dessert
            </option>
        </select>

        <label class="choix-plat-actif">
            <input
                type="checkbox"
                name="actif"
                <?= $plat['actif'] ? 'checked' : '' ?>
            >
            Plat actif
        </label>

        <button type="submit" class="bouton-formulaire-plat">Modifier le plat</button>

    </form>

    <a href="gestion-plats.php" class="bouton-retour-plats">Retour à la gestion des plats</a>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
