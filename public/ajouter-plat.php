<?php
session_start();

require_once __DIR__ . '/../includes/db.php';

$pageTitle = 'Ajouter un plat';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$role = (int) $_SESSION['utilisateur_role'];

if ($role !== 2 && $role !== 1) {
    header('Location: connexion.php');
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
            INSERT INTO plat (
                nom,
                description,
                type_plat,
                actif
            )
            VALUES (?, ?, ?, ?)
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $nom,
            $description,
            $typePlat,
            $actif
        ]);

        header('Location: gestion-plats.php');
        exit();
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<main class="ajouter-plat-page">

    <h1>Ajouter un plat</h1>

    <?php if (!empty($messageErreur)) : ?>
        <p><?= htmlspecialchars($messageErreur) ?></p>
    <?php endif; ?>

    <form method="POST">

        <label for="nom">Nom du plat</label>
        <input type="text" id="nom" name="nom" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" required></textarea>

        <label for="type_plat">Type de plat</label>
        <select name="type_plat" id="type_plat" required>
            <option value="">-- Choisir un type --</option>
            <option value="entrée">Entrée</option>
            <option value="plat">Plat</option>
            <option value="dessert">Dessert</option>
        </select>

        <label>
            <input type="checkbox" name="actif" checked>
            Plat actif
        </label>

        <button type="submit">Ajouter le plat</button>

    </form>

    <a href="gestion-plats.php">Retour à la gestion des plats</a>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>