<?php
session_start();

require_once __DIR__ . '/../includes/db.php';

$pageTitle = 'Ajouter un menu';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$role = (int) $_SESSION['utilisateur_role'];

if ($role !== 2 && $role !== 1) {
    header('Location: connexion.php');
    exit();
}

/* Récupération des thèmes */
$stmtThemes = $pdo->prepare("SELECT id, libelle FROM theme ORDER BY libelle ASC");
$stmtThemes->execute();
$themes = $stmtThemes->fetchAll(PDO::FETCH_ASSOC);

/* Récupération des régimes */
$stmtRegimes = $pdo->prepare("SELECT id, libelle FROM regime ORDER BY libelle ASC");
$stmtRegimes->execute();
$regimes = $stmtRegimes->fetchAll(PDO::FETCH_ASSOC);

/* Récupération des plats actifs */
$stmtPlats = $pdo->prepare("
    SELECT id, nom, type_plat
    FROM plat
    WHERE actif = 1
    ORDER BY type_plat, nom ASC
");
$stmtPlats->execute();
$plats = $stmtPlats->fetchAll(PDO::FETCH_ASSOC);

$messageErreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $nombrePersonneMin = (int) ($_POST['nombre_personne_min'] ?? 0);
    $prixMin = (float) ($_POST['prix_min'] ?? 0);
    $conditionMenu = trim($_POST['condition_menu'] ?? '');
    $stockDisponible = (int) ($_POST['stock_disponible'] ?? 0);
    $themeId = (int) ($_POST['theme_id'] ?? 0);
    $regimeId = (int) ($_POST['regime_id'] ?? 0);
    $platsChoisis = $_POST['plats'] ?? [];
    $actif = isset($_POST['actif']) ? 1 : 0;

    if (
        empty($titre) ||
        empty($description) ||
        $nombrePersonneMin <= 0 ||
        $prixMin <= 0 ||
        empty($conditionMenu) ||
        $stockDisponible < 0 ||
        $themeId <= 0 ||
        $regimeId <= 0 ||
        empty($platsChoisis)
    ) {
        $messageErreur = 'Veuillez remplir correctement tous les champs et choisir au moins un plat.';
    } else {
        $sql = "
            INSERT INTO menu (
                titre,
                description,
                nombre_personne_min,
                prix_min,
                condition_menu,
                stock_disponible,
                actif,
                theme_id,
                regime_id
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $titre,
            $description,
            $nombrePersonneMin,
            $prixMin,
            $conditionMenu,
            $stockDisponible,
            $actif,
            $themeId,
            $regimeId
        ]);

        $menuId = $pdo->lastInsertId();

        $sqlMenuPlat = "
            INSERT INTO menu_plat (menu_id, plat_id)
            VALUES (?, ?)
        ";

        $stmtMenuPlat = $pdo->prepare($sqlMenuPlat);

        foreach ($platsChoisis as $platId) {
            $stmtMenuPlat->execute([
                $menuId,
                (int) $platId
            ]);
        }

        header('Location: gestion-menus.php');
        exit();
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<main class="employe-ajouter-menu-page">

    <h1>Ajouter un menu</h1>

    <?php if (!empty($messageErreur)) : ?>
        <p><?= htmlspecialchars($messageErreur) ?></p>
    <?php endif; ?>

    <form method="POST">

        <label for="titre">Titre du menu</label>
        <input type="text" id="titre" name="titre" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" required></textarea>

        <label for="theme_id">Thème</label>
        <select name="theme_id" id="theme_id" required>
            <option value="">-- Choisir un thème --</option>
            <?php foreach ($themes as $theme) : ?>
                <option value="<?= htmlspecialchars($theme['id']) ?>">
                    <?= htmlspecialchars($theme['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="regime_id">Régime</label>
        <select name="regime_id" id="regime_id" required>
            <option value="">-- Choisir un régime --</option>
            <?php foreach ($regimes as $regime) : ?>
                <option value="<?= htmlspecialchars($regime['id']) ?>">
                    <?= htmlspecialchars($regime['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="nombre_personne_min">Nombre minimum de personnes</label>
        <input type="number" id="nombre_personne_min" name="nombre_personne_min" min="1" required>

        <label for="prix_min">Prix minimum</label>
        <input type="number" id="prix_min" name="prix_min" min="0" step="0.01" required>

        <label for="condition_menu">Conditions du menu</label>
        <textarea id="condition_menu" name="condition_menu" required></textarea>

        <label for="stock_disponible">Stock disponible</label>
        <input type="number" id="stock_disponible" name="stock_disponible" min="0" required>

        <fieldset>
            <legend>Plats associés au menu</legend>

            <?php foreach ($plats as $plat) : ?>
                <label>
                    <input
                        type="checkbox"
                        name="plats[]"
                        value="<?= htmlspecialchars($plat['id']) ?>"
                    >
                    <?= htmlspecialchars($plat['type_plat'] . ' - ' . $plat['nom']) ?>
                </label>
                <br>
            <?php endforeach; ?>
        </fieldset>

        <label>
            <input type="checkbox" name="actif" checked>
            Menu actif
        </label>

        <button type="submit">Ajouter le menu</button>

    </form>

    <a href="gestion-menus.php">Retour à la gestion des menus</a>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>