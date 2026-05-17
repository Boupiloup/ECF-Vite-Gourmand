<?php
session_start();

require_once __DIR__ . '/../includes/db.php';

$pageTitle = "Chiffre d'affaires";

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: connexion.php");
    exit();
}

$role = (int) $_SESSION['utilisateur_role'];

if ($role !== 1) {
    header("Location: connexion.php");
    exit();
}

// On récupère tous les menus pour remplir la liste déroulante
$sqlMenus = "SELECT id, titre FROM menu ORDER BY titre ASC";
$stmtMenus = $pdo->prepare($sqlMenus);
$stmtMenus->execute();
$menus = $stmtMenus->fetchAll(PDO::FETCH_ASSOC);

// On récupère les valeurs du formulaire
$menuId = $_GET['menu_id'] ?? '';
$dateDebut = $_GET['date_debut'] ?? '';
$dateFin = $_GET['date_fin'] ?? '';

// Au départ, il n'y a pas encore de résultat
$chiffreAffaires = null;

// Si le formulaire a été envoyé
if (!empty($_GET)) {

    $sqlCA = "SELECT SUM(prix_total) AS chiffre_affaires FROM commande WHERE 1 = 1";

    $params = [];

    // Si un menu est choisi
    if (!empty($menuId)) {
        $sqlCA = $sqlCA . " AND menu_id = ?";
        $params[] = $menuId;
    }

    // Si une date de début est choisie
    if (!empty($dateDebut)) {
        $sqlCA = $sqlCA . " AND date_commande >= ?";
        $params[] = $dateDebut . " 00:00:00";
    }

    // Si une date de fin est choisie
    if (!empty($dateFin)) {
        $sqlCA = $sqlCA . " AND date_commande <= ?";
        $params[] = $dateFin . " 23:59:59";
    }

    $stmtCA = $pdo->prepare($sqlCA);
    $stmtCA->execute($params);
    $resultat = $stmtCA->fetch(PDO::FETCH_ASSOC);

    $chiffreAffaires = $resultat['chiffre_affaires'];

    if ($chiffreAffaires === null) {
        $chiffreAffaires = 0;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<main class="admin-ca">
    <h1>Chiffre d'affaires par menu</h1>

    <form method="GET">
        <label for="menu_id">Menu</label>

        <select name="menu_id" id="menu_id">
            <option value="">Tous les menus</option>

            <?php foreach ($menus as $menu): ?>
                <option value="<?= $menu['id'] ?>" <?php if ($menuId == $menu['id'])
                      echo 'selected'; ?>>
                    <?= htmlspecialchars($menu['titre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="date_debut">Date de début</label>
        <input type="date" name="date_debut" id="date_debut" value="<?= htmlspecialchars($dateDebut) ?>">

        <label for="date_fin">Date de fin</label>
        <input type="date" name="date_fin" id="date_fin" value="<?= htmlspecialchars($dateFin) ?>">

        <button type="submit">Filtrer</button>
    </form>

    <!-- Si un calcul a été effectué, on affiche le chiffre d'affaires trouvé -->
    <?php if ($chiffreAffaires !== null): ?>
        <section>
            <h2>Résultat</h2>

            <p>
                Chiffre d'affaires :
                <strong>
                    <?= number_format((float) $chiffreAffaires, 2, ',', ' ') ?> €
                </strong>
            </p>
        </section>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>