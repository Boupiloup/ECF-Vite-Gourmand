<?php
session_start();
$pageTitle = "Mes commandes";

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];

require_once '../includes/db.php';

$sql = "
    SELECT 
        commande.id,
        commande.date_commande,
        commande.date_prestation,
        commande.heure_livraison,
        commande.adresse_livraison,
        commande.ville_livraison,
        commande.code_postal,
        commande.nb_personnes,
        commande.prix_total,
        menu.titre AS menu_titre
    FROM commande
    INNER JOIN menu ON commande.menu_id = menu.id
    WHERE commande.utilisateur_id = ?
    ORDER BY commande.date_commande DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$utilisateur_id]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once __DIR__ . '/../includes/header.php';
?>

<main class="mes-commandes-page">

    <h1>Mes commandes</h1>

    <?php if (empty($commandes)): ?>

        <p>Vous n'avez pas encore passé de commande.</p>

    <?php else: ?>

        <div class="table-container">
            <table class="commande-table">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Commande passée</th>
                        <th>Date de prestation</th>
                        <th>Heure de livraison</th>
                        <th>Adresse de livraison</th>
                        <th>Ville</th>
                        <th>Code postal</th>
                        <th>Personnes</th>
                        <th>Prix total</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($commandes as $commande): ?>
                        <tr>
                            <td><?= htmlspecialchars($commande['menu_titre']) ?></td>
                            <td><?= htmlspecialchars($commande['date_commande']) ?></td>
                            <td><?= htmlspecialchars($commande['date_prestation']) ?></td>
                            <td><?= htmlspecialchars($commande['heure_livraison']) ?></td>
                            <td><?= htmlspecialchars($commande['adresse_livraison']) ?></td>
                            <td><?= htmlspecialchars($commande['ville_livraison']) ?></td>
                            <td><?= htmlspecialchars($commande['code_postal']) ?></td>
                            <td><?= htmlspecialchars($commande['nb_personnes']) ?></td>
                            <td><?= htmlspecialchars($commande['prix_total']) ?> €</td>
                            <td>
                                <a href="detail-commande.php?id=<?= htmlspecialchars($commande['id']) ?>">
                                    Voir détail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>

</main>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>