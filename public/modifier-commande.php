<?php
session_start();
$pageTitle = "Modifier ma commande";

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
$message = null;

require_once '../includes/db.php';

$sql = "SELECT commande.*, menu.prix_min, menu.nombre_personne_min
        FROM commande
        INNER JOIN menu ON commande.menu_id = menu.id
        WHERE commande.id = ?
        AND commande.utilisateur_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$commande_id, $utilisateur_id]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande) {
    header('Location: mes-commandes.php');
    exit;
}

if ($commande['statut_commande'] !== 'en_attente') {
    header('Location: detail-commande.php?id=' . $commande_id);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date_prestation = trim($_POST['date_prestation'] ?? '');
    $heure_livraison = trim($_POST['heure_livraison'] ?? '');
    $adresse_livraison = trim($_POST['adresse_livraison'] ?? '');
    $ville_livraison = trim(strtolower($_POST['ville_livraison'] ?? ''));
    $code_postal = trim($_POST['code_postal'] ?? '');
    $nb_personnes = (int) ($_POST['nb_personnes'] ?? 0);

    if (
        empty($date_prestation) ||
        empty($heure_livraison) ||
        empty($adresse_livraison) ||
        empty($ville_livraison) ||
        empty($code_postal) ||
        empty($nb_personnes)
    ) {
        $message = "Tous les champs sont obligatoires.";
    } elseif ($nb_personnes < $commande['nombre_personne_min']) {
        $message = "Le nombre de personnes est inférieur au minimum du menu.";
    } else {
        $prixParPersonne = $commande['prix_min'] / $commande['nombre_personne_min'];
        $prixTotal = $prixParPersonne * $nb_personnes;

        $reduction = 0;

        if ($nb_personnes >= ($commande['nombre_personne_min'] + 5)) {
            $reduction = $prixTotal * 0.1;
        }

        $prix_livraison = ($ville_livraison === 'bordeaux') ? 0 : 5;

        $totalFinal = $prixTotal - $reduction + $prix_livraison;

        $sqlUpdate = "UPDATE commande
                      SET date_prestation = ?,
                          heure_livraison = ?,
                          adresse_livraison = ?,
                          ville_livraison = ?,
                          code_postal = ?,
                          nb_personnes = ?,
                          prix_total = ?,
                          prix_livraison = ?
                      WHERE id = ?
                      AND utilisateur_id = ?";

        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([
            $date_prestation,
            $heure_livraison,
            $adresse_livraison,
            $ville_livraison,
            $code_postal,
            $nb_personnes,
            $totalFinal,
            $prix_livraison,
            $commande_id,
            $utilisateur_id
        ]);

        header('Location: detail-commande.php?id=' . $commande_id);
        exit;
    }
}

include_once __DIR__ . '/../includes/header.php';
?>

<main class="modifier-commande-page">

    <h1>Modifier ma commande</h1>

    <?php if ($message): ?>
        <p class="error-message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form action="modifier-commande.php?id=<?= htmlspecialchars($commande['id']) ?>" method="post" class="modifier-commande-form">

        <div class="form-group">
            <label for="date_prestation">Date de prestation</label>
            <input type="date" id="date_prestation" name="date_prestation" value="<?= htmlspecialchars($commande['date_prestation']) ?>" required>
        </div>

        <div class="form-group">
            <label for="heure_livraison">Heure de livraison</label>
            <input type="time" id="heure_livraison" name="heure_livraison" value="<?= htmlspecialchars($commande['heure_livraison']) ?>" required>
        </div>

        <div class="form-group">
            <label for="adresse_livraison">Adresse de livraison</label>
            <input type="text" id="adresse_livraison" name="adresse_livraison" value="<?= htmlspecialchars($commande['adresse_livraison']) ?>" required>
        </div>

        <div class="form-group">
            <label for="ville_livraison">Ville</label>
            <input type="text" id="ville_livraison" name="ville_livraison" value="<?= htmlspecialchars($commande['ville_livraison']) ?>" required>
        </div>

        <div class="form-group">
            <label for="code_postal">Code postal</label>
            <input type="text" id="code_postal" name="code_postal" value="<?= htmlspecialchars($commande['code_postal']) ?>" required>
        </div>

        <div class="form-group">
            <label for="nb_personnes">Nombre de personnes</label>
            <input type="number" id="nb_personnes" name="nb_personnes" value="<?= htmlspecialchars($commande['nb_personnes']) ?>" min="<?= htmlspecialchars($commande['nombre_personne_min']) ?>" required>
        </div>

        <button type="submit" class="btn-submit-commande">
            Enregistrer les modifications
        </button>

        <a href="detail-commande.php?id=<?= htmlspecialchars($commande['id']) ?>" class="btn-retour-detail">
            Retour au détail
        </a>

    </form>

</main>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>