<?php
session_start();
$pageTitle = "Mes commandes";

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];

require_once '../includes/db.php';

$sql = "SELECT * FROM commande WHERE utilisateur_id = ? ORDER BY date_commande DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$utilisateur_id]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once __DIR__ . '/../includes/header.php';
?>

<main>

    <h1>Mes commandes</h1>

    <?php if (empty($commandes)): ?>
        <p>Vous n'avez pas encore passé de commande.</p>
    <?php else: ?>
        <table class="commande-table">
            <tr>
                <th>Commande passée</th>
                <th>Date de la prestation</th>
                <th>Heure de la livraison</th>
                <th>Adresse de la livraison</th>
                <th>Ville</th>
                <th>Code postal</th>
                <th>Nombre de personnes</th>
                <th>Prix total</th>
            </tr>
            <?php foreach ($commandes as $commande): ?>

                <tr>
                    <td> <?= htmlspecialchars($commande['date_commande']) ?> </td>
                    <td> <?= htmlspecialchars($commande['date_prestation']) ?> </td>
                    <td> <?= htmlspecialchars($commande['heure_livraison']) ?> </td>
                    <td> <?= htmlspecialchars($commande['adresse_livraison']) ?> </td>
                    <td> <?= htmlspecialchars($commande['ville_livraison']) ?> </td>
                    <td> <?= htmlspecialchars($commande['code_postal']) ?> </td>
                    <td> <?= htmlspecialchars($commande['nb_personnes']) ?> </td>
                    <td> <?= htmlspecialchars($commande['prix_total']) ?> </td>
                </tr>
            <?php endforeach; ?>

        </table>
    <?php endif; ?>
</main>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>