<?php
session_start();

require_once __DIR__ . '/../includes/mongodb.php';

$pageTitle = 'Statistiques admin';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$role = (int) $_SESSION['utilisateur_role'];

if ($role !== 1) {
    header('Location: connexion.php');
    exit();
}

$collection = $mongoDatabase->stats_menus;
$stats = $collection->find()->toArray();

$labels = [];
$commandes = [];

foreach ($stats as $stat) {
    $labels[] = $stat['menu_titre'];
    $commandes[] = (int) $stat['nombre_commandes'];
}

require_once __DIR__ . '/../includes/header.php';
?>

<main class="admin-stats">
    <h1>Statistiques des commandes par menu</h1>

    <a href="admin-generer-stats.php">Mettre à jour les statistiques</a>

    <table>
        <thead>
            <tr>
                <th>Menu</th>
                <th>Nombre de commandes</th>
                <th>Chiffre d'affaires</th>
                <th>Date de génération</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($stats as $stat): ?>
                <tr>
                    <td><?= htmlspecialchars($stat['menu_titre']) ?></td>
                    <td><?= htmlspecialchars($stat['nombre_commandes']) ?></td>
                    <td><?= htmlspecialchars($stat['chiffre_affaires']) ?> €</td>
                    <td><?= htmlspecialchars($stat['date_generation']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Comparaison des menus</h2>

    <canvas id="graphiqueMenus"></canvas>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const labels = <?= json_encode($labels) ?>;
    const commandes = <?= json_encode($commandes) ?>;

    const ctx = document.getElementById('graphiqueMenus');

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Nombre de commandes',
                data: commandes
            }]
        }
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>