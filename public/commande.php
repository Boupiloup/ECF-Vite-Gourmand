<?php
$pageTitle = "Commander un menu";
require_once '../includes/db.php';

/* Sécurité : utilisateur connecté obligatoire */
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];

/* Récupération utilisateur */
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id = ?");
$stmt->execute([$utilisateur_id]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

/* Variables */
$message = null;
$personnes = null;
$prixTotal = null;
$livraison = 0;
$totalFinal = null;
$reduction = 0;

/* Récupération menu */
$menuId = $_GET['menu_id'] ?? null;

if ($menuId !== null) {
    $stmt = $pdo->prepare("SELECT * FROM menu WHERE id = ?");
    $stmt->execute([$menuId]);
    $menu = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $menu = null;
}

/* Traitement formulaire */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $menu) {

    $personnes = (int) ($_POST['personnes'] ?? 0);
    $ville = trim(strtolower($_POST['ville'] ?? ''));
    $adresse = trim($_POST['adresse'] ?? '');
    $date = $_POST['date'] ?? '';
    $heure = $_POST['heure'] ?? '';
    $code_postal = trim($_POST['code_postal'] ?? '');

    /* VALIDATION AJOUTÉE */
    if (
        empty($personnes) ||
        empty($ville) ||
        empty($adresse) ||
        empty($date) ||
        empty($heure) ||
        empty($code_postal)
    ) {
        $message = "Tous les champs sont obligatoires.";
    } elseif ($personnes < $menu['nombre_personne_min']) {
        $message = "Pas assez de personnes.";
    } elseif ($personnes > (int) $menu['stock_disponible']) {
        $message = "Stock insuffisant.";
    } else {

        /* Calcul prix */
        $prixParPersonne = $menu['prix_min'] / $menu['nombre_personne_min'];
        $prixTotal = $prixParPersonne * $personnes;

        if ($personnes >= ($menu['nombre_personne_min'] + 5)) {
            $reduction = $prixTotal * 0.1;
        }

        /**
         * Livraison simplifiée
         */
        $livraison = ($ville === 'bordeaux') ? 0 : 5;

        $totalFinal = $prixTotal - $reduction + $livraison;

        /* INSERT CORRECT */
        $sql = "INSERT INTO commande (
            utilisateur_id,
            menu_id,
            nb_personnes,
            ville_livraison,
            adresse_livraison,
            date_prestation,
            heure_livraison,
            prix_total,
            prix_livraison,
            code_postal
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $utilisateur_id,
            $menuId,
            $personnes,
            $ville,
            $adresse,
            $date,
            $heure,
            $totalFinal,
            $livraison,
            $code_postal
        ]);

        /* Mise à jour stock*/
        $nouveauStock = (int) $menu['stock_disponible'] - $personnes;

        $stmtUpdate = $pdo->prepare("UPDATE menu SET stock_disponible = ? WHERE id = ?");
        $stmtUpdate->execute([$nouveauStock, $menuId]);

        $menu['stock_disponible'] = $nouveauStock;

        $message = "Commande enregistrée avec succès.";
    }
}

include_once __DIR__ . '/../includes/header.php';
?>

<main>
    <section class="commande-hero">
        <div class="hero-content">

            <?php if ($menu): ?>
                <h1>Votre commande</h1>

                <h2><?= htmlspecialchars($menu['titre']); ?></h2>

                <p>
                    <strong>Minimum de commande :</strong>
                    <?= htmlspecialchars($menu['nombre_personne_min']); ?> personnes
                </p>

                <p>
                    <strong>Prix pour <?= htmlspecialchars($menu['nombre_personne_min']); ?> personnes :</strong>
                    <?= htmlspecialchars($menu['prix_min']); ?> €
                </p>

            <?php else: ?>
                <h1>Menu non trouvé</h1>
            <?php endif; ?>

        </div>
    </section>

    <?php if ($menu): ?>
        <section class="commande-section">
            <div class="commande-container">

                <div class="commande-gauche">
                    <h2>Informations de la prestation</h2>

                    <form method="POST">
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="text" id="nom" name="nom"
                                value="<?= htmlspecialchars($utilisateur['nom'] ?? '') ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="prenom">Prénom</label>
                            <input type="text" id="prenom" name="prenom"
                                value="<?= htmlspecialchars($utilisateur['prenom'] ?? '') ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email"
                                value="<?= htmlspecialchars($utilisateur['email'] ?? '') ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="telephone">Téléphone</label>
                            <input type="tel" id="telephone" name="telephone"
                                value="<?= htmlspecialchars($utilisateur['telephone'] ?? '') ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="personnes">Nombre de personnes</label>
                            <input type="number" id="personnes" name="personnes" min="1" required>
                        </div>

                        <div class="form-group">
                            <label for="ville">Ville</label>
                            <input type="text" id="ville" name="ville" required>
                        </div>

                        <div class="form-group">
                            <label for="code_postal">Code postal</label>
                            <input type="text" id="code_postal" name="code_postal" required>
                        </div>

                        <div class="form-group">
                            <label for="adresse">Adresse de la prestation</label>
                            <input type="text" id="adresse" name="adresse" required>
                        </div>

                        <div class="form-group">
                            <label for="heure">Heure souhaitée</label>
                            <input type="time" id="heure" name="heure" required>
                        </div>

                        <div class="form-group">
                            <label for="date">Date de la prestation</label>
                            <input type="date" id="date" name="date" required>
                        </div>

                        <button type="submit" class="button_command">Passer la commande</button>

                        <?php if ($message): ?>
                            <p class="message"><?= htmlspecialchars($message) ?></p>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="commande-droite">
                    <h2>Résumé de la commande</h2>

                    <p>
                        <strong>Menu choisi :</strong>
                        <?= htmlspecialchars($menu['titre']); ?>
                    </p>

                    <p>
                        <strong>Minimum de commande :</strong>
                        <?= htmlspecialchars($menu['nombre_personne_min']); ?> personnes
                    </p>

                    <p>
                        <strong>Prix pour <?= htmlspecialchars($menu['nombre_personne_min']); ?> personnes :</strong>
                        <?= htmlspecialchars($menu['prix_min']); ?> €
                    </p>

                    <p>
                        <strong>Stock disponible :</strong>
                        <?= htmlspecialchars($menu['stock_disponible']); ?>
                    </p>

                    <p id="livraisonTempReel">
                        <strong>Livraison :</strong>
                        <?= htmlspecialchars($livraison) ?> €
                    </p>

                    <p id="reductionTempReel">
                        <strong>Réduction :</strong>
                        <?= htmlspecialchars($reduction) ?> €
                    </p>

                    <p id="totalTempReel">
                        <strong>Total estimé :</strong>
                        <?php if ($totalFinal !== null): ?>
                            <?= htmlspecialchars($totalFinal); ?> €
                        <?php else: ?>
                            À calculer
                        <?php endif; ?>
                    </p>

                </div>
            </div>
        </section>
    <?php endif; ?>

</main>

<script>
    const menuData = {
        prixMin: <?= $menu['prix_min'] ?>,
        minPersonnes: <?= $menu['nombre_personne_min'] ?>
    };
</script>

<script src="assets/js/calculTempReel.js"></script>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>