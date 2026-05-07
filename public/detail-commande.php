<?php
session_start();
$pageTitle = "Détail de la commande";

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: mes-commandes.php');
    exit;
}

$commande_id = (int) $_GET['id'];
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
        commande.prix_livraison,
        commande.distance_km,
        menu.titre AS menu_titre,
        menu.description AS menu_description,
        menu.condition_menu
    FROM commande 
    INNER JOIN menu ON commande.menu_id = menu.id
    WHERE commande.id = ? 
    AND commande.utilisateur_id = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$commande_id, $utilisateur_id]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande) {
    header('Location: mes-commandes.php');
    exit;
}

include_once __DIR__ . '/../includes/header.php';
?>

<main class="detail-commande-page">
    <h1 class="detail-commande-title">Détail de la commande</h1>

    <section class="commande-detail-card">
        <h2 class="commande-detail-menu-title">
            <?= htmlspecialchars($commande['menu_titre']) ?>
        </h2>

        <div class="commande-detail-block">
            <h3>Informations du menu</h3>

            <div class="commande-info-row">
                <span>Description</span>
                <strong><?= htmlspecialchars($commande['menu_description']) ?></strong>
            </div>

            <div class="commande-info-row">
                <span>Conditions</span>
                <strong><?= htmlspecialchars($commande['condition_menu']) ?></strong>
            </div>
        </div>

        <div class="commande-detail-block">
            <h3>Informations de livraison</h3>

            <div class="commande-info-row">
                <span>Date de commande</span>
                <strong><?= htmlspecialchars($commande['date_commande']) ?></strong>
            </div>

            <div class="commande-info-row">
                <span>Date de prestation</span>
                <strong><?= htmlspecialchars($commande['date_prestation']) ?></strong>
            </div>

            <div class="commande-info-row">
                <span>Heure de livraison</span>
                <strong><?= htmlspecialchars($commande['heure_livraison']) ?></strong>
            </div>

            <div class="commande-info-row">
                <span>Adresse</span>
                <strong><?= htmlspecialchars($commande['adresse_livraison']) ?></strong>
            </div>

            <div class="commande-info-row">
                <span>Ville</span>
                <strong><?= htmlspecialchars($commande['ville_livraison']) ?></strong>
            </div>

            <div class="commande-info-row">
                <span>Code postal</span>
                <strong><?= htmlspecialchars($commande['code_postal']) ?></strong>
            </div>
        </div>

        <div class="commande-detail-block">
            <h3>Résumé du prix</h3>

            <div class="commande-info-row">
                <span>Nombre de personnes</span>
                <strong><?= htmlspecialchars($commande['nb_personnes']) ?></strong>
            </div>

            <div class="commande-info-row">
                <span>Livraison</span>
                <strong><?= htmlspecialchars($commande['prix_livraison']) ?> €</strong>
            </div>

            <div class="commande-info-row total-row">
                <span>Total</span>
                <strong><?= htmlspecialchars($commande['prix_total']) ?> €</strong>
            </div>
        </div>

        <div class="commande-detail-actions">
            <a href="mes-commandes.php" class="commande-detail-back">
                Retour à mes commandes
            </a>
        </div>
    </section>
</main>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>