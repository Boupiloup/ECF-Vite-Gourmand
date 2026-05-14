<?php
session_start();

require_once __DIR__ . '/../includes/db.php';

$pageTitle = 'Gestion des horaires';

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
        jour,
        heure_ouverture,
        heure_fermeture
    FROM horaire
    ORDER BY FIELD(jour, 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche')
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$horaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<main class="gestion-horaires-page">

    <h1>Gestion des horaires</h1>

    <?php if (empty($horaires)) : ?>

        <p>Aucun horaire enregistré pour le moment.</p>

    <?php else : ?>

        <table>
            <thead>
                <tr>
                    <th>Jour</th>
                    <th>Ouverture</th>
                    <th>Fermeture</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($horaires as $horaire) : ?>
                    <tr>
                        <td><?= htmlspecialchars($horaire['jour']) ?></td>
                        <td><?= htmlspecialchars($horaire['heure_ouverture']) ?></td>
                        <td><?= htmlspecialchars($horaire['heure_fermeture']) ?></td>
                        <td>
                            <a href="modifier-horaire.php?id=<?= htmlspecialchars($horaire['id']) ?>">
                                Modifier
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>