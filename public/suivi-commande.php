<?php
session_start();
$pageTitle = "Suivi de mes commandes";

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
        commande.statut_commande,
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

<main class="suivi-commande-page">

    <h1>Suivi de mes commandes</h1>

    <?php if (empty($commandes)): ?>

        <p>Vous n'avez aucune commande à suivre.</p>

    <?php else: ?>

        <?php foreach ($commandes as $commande): ?>

            <section class="suivi-commande-card">

                <h2><?= htmlspecialchars($commande['menu_titre']) ?></h2>

                <p>
                    <strong>Date de commande :</strong>
                    <?= htmlspecialchars($commande['date_commande']) ?>
                </p>

                <p>
                    <strong>Date de prestation :</strong>
                    <?= htmlspecialchars($commande['date_prestation']) ?>
                </p>

                <p>
                    <strong>Heure de livraison :</strong>
                    <?= htmlspecialchars($commande['heure_livraison']) ?>
                </p>

                <p>
                    <strong>Statut :</strong>

                    <?php if ($commande['statut_commande'] === 'en_attente'): ?>
                        En attente de validation
                    <?php elseif ($commande['statut_commande'] === 'accepte'): ?>
                        Commande acceptée
                    <?php elseif ($commande['statut_commande'] === 'annulee'): ?>
                        Commande annulée
                    <?php elseif ($commande['statut_commande'] === 'en_preparation'): ?>
                        En préparation
                    <?php elseif ($commande['statut_commande'] === 'en_cours_de_livraison'): ?>
                        En cours de livraison
                    <?php elseif ($commande['statut_commande'] === 'livree'): ?>
                        Livrée
                    <?php elseif ($commande['statut_commande'] === 'en_attente_retour_materiel'): ?>
                        En attente du retour matériel
                    <?php elseif ($commande['statut_commande'] === 'terminee'): ?>
                        Terminée
                    <?php endif; ?>
                </p>

                <a href="detail-commande.php?id=<?= htmlspecialchars($commande['id']) ?>">
                    Voir le détail
                </a>

            </section>

        <?php endforeach; ?>

    <?php endif; ?>

</main>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>