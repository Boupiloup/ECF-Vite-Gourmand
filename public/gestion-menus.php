<?php
session_start();

require_once __DIR__ . '/../includes/db.php';

$pageTitle = 'Gestion des menus';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$role = (int) $_SESSION['utilisateur_role'];

if ($role !== 2 && $role !== 1) {
    header('Location: connexion.php');
    exit();
}

$sql = "
    SELECT
        id,
        titre,
        description,
        nombre_personne_min,
        prix_min,
        stock_disponible,
        actif
    FROM menu
    ORDER BY id DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<main class="employe-menus-page">

    <h1>Gestion des menus</h1>

    <p class="employe-menus-intro">
        Depuis cette page, vous pouvez gérer les menus proposés par Vite & Gourmand.
    </p>

    <a href="employe-ajouter-menu.php" class="employe-menus-ajouter">
        Ajouter un menu
    </a>

    <?php if (empty($menus)): ?>

        <p class="employe-menus-vide">Aucun menu enregistré pour le moment.</p>

    <?php else: ?>

        <table class="employe-menus-table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Personnes min.</th>
                    <th>Prix min.</th>
                    <th>Stock</th>
                    <th>Actif</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($menus as $menu): ?>
                    <tr>
                        <td><?= htmlspecialchars($menu['titre']) ?></td>
                        <td><?= htmlspecialchars($menu['description']) ?></td>
                        <td><?= htmlspecialchars($menu['nombre_personne_min']) ?></td>
                        <td><?= htmlspecialchars($menu['prix_min']) ?> €</td>
                        <td><?= htmlspecialchars($menu['stock_disponible']) ?></td>
                        <td><?= $menu['actif'] ? 'Oui' : 'Non' ?></td>
                        <td>
                            <a href="modifier-menu.php?id=<?= htmlspecialchars($menu['id']) ?>" class="employe-menus-action">
                                Modifier
                            </a>

                            <a href="supprimer-menu.php?id=<?= htmlspecialchars($menu['id']) ?>" class="employe-menus-action-danger">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
