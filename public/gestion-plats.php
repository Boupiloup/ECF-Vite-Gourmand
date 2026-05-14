<?php
session_start();

require_once __DIR__ . '/../includes/db.php';

$pageTitle = 'Gestion des plats';

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
        nom,
        description,
        type_plat,
        actif
    FROM plat
    ORDER BY type_plat, nom ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$plats = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<main class="gestion-plats-page">

    <h1>Gestion des plats</h1>

    <a href="ajouter-plat.php">Ajouter un plat</a>

    <?php if (empty($plats)): ?>

        <p>Aucun plat enregistré pour le moment.</p>

    <?php else: ?>

        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Actif</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($plats as $plat): ?>
                    <tr>
                        <td><?= htmlspecialchars($plat['nom']) ?></td>
                        <td><?= htmlspecialchars($plat['description']) ?></td>
                        <td><?= htmlspecialchars($plat['type_plat']) ?></td>
                        <td><?= $plat['actif'] ? 'Oui' : 'Non' ?></td>
                        <td>
                            <a href="modifier-plat.php?id=<?= htmlspecialchars($plat['id']) ?>">
                                Modifier
                            </a>

                            <a href="supprimer-plat.php?id=<?= htmlspecialchars($plat['id']) ?>">
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