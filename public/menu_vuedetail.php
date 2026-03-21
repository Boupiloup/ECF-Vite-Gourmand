<?php

$pageTitle = 'détail du menu';
require_once __DIR__ . '/../includes/db.php';
include_once __DIR__ . '/../includes/header.php';

// On récupère l'id envoyé dans l'URL (ex : ?id=2)
// Si aucun id n'est présent, on met null
$id = $_GET['id'] ?? null;

// On initialise $menu pour éviter l'erreur "Undefined variable"
$menu = null;

// Si un id est présent, on va chercher le menu correspondant
if ($id !== null) {

    // Prépare une requête SQL sécurisée
    $stmt = $pdo->prepare('SELECT * FROM menu WHERE id = ?');

    // Exécute la requête avec l'id
    $stmt->execute([$id]);

    // Récupère le résultat (un seul menu)
    $menu = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<main>

    <?php if ($menu): ?>

        <h1><?= htmlspecialchars($menu['titre']) ?></h1>

        <p>
            <strong>Description :</strong><br>
            <?= htmlspecialchars($menu['description']) ?>
        </p>

        <p>
            <strong>Personnes minimum :</strong>
            <?= htmlspecialchars($menu['nombre_personne_min']) ?>
        </p>

        <p>
            <strong>Prix :</strong>
            <?= htmlspecialchars($menu['prix_min']) ?> €
        </p>

        <p>
            <strong>Conditions :</strong>
            <?= htmlspecialchars($menu['condition_menu']) ?>
        </p>

        <p>
            <strong>Stock disponible :</strong>
            <?= htmlspecialchars($menu['stock_disponible']) ?>
        </p>

    <?php else: ?>

        <p>Menu introuvable</p>

    <?php endif; ?>

</main>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>