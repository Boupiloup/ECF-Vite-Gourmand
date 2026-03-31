<?php

$pageTitle = 'détail du menu';
require_once __DIR__ . '/../includes/db.php';
include_once __DIR__ . '/../includes/header.php';

// On récupère l'id envoyé dans l'URL (ex : ?id=2)
// Si aucun id n'est présent, on met null
$id = $_GET['id'] ?? null;

// On initialise $menu pour éviter l'erreur "Undefined variable"
$menu = null;
$images = [];
$entrees = [];
$plats = [];
$desserts = [];
$allergenes = [];

// Si un id est présent, on va chercher le menu correspondant
if ($id !== null) {

    // Menu //

    // Prépare une requête SQL sécurisée
    $stmt = $pdo->prepare(
        'SELECT menu.*, theme.libelle AS theme_nom, regime.libelle AS regime_nom
         FROM menu
         JOIN theme ON menu.theme_id = theme.id
         JOIN regime ON menu.regime_id = regime.id
         WHERE menu.id = ?'
    );

    // Exécute la requête avec l'id
    $stmt->execute([$id]);

    // Récupère le résultat (un seul menu)
    $menu = $stmt->fetch(PDO::FETCH_ASSOC);

    // IMAGE //

    // Récupère toutes les images liées au menu
    $stmtImages = $pdo->prepare('SELECT * FROM image WHERE menu_id = ?');

    // Exécute la requête avec l'id du menu
    $stmtImages->execute([$id]);

    // Récupère toutes les images sous forme de tableau 
    $images = $stmtImages->fetchAll(PDO::FETCH_ASSOC);

    // PLATS DU MENU //

    // Prépare une requête SQL pour récupérer les plats liés au menu
    $stmtPlats = $pdo->prepare(
        'SELECT plat.* 
         FROM menu_plat 
         JOIN plat ON menu_plat.plat_id = plat.id 
         WHERE menu_plat.menu_id = ?'
    );

    // Exécute la requête avec l'id du menu
    $stmtPlats->execute([$id]);

    // Récupère tous les plats du menu sous forme de tableau
    $platsMenu = $stmtPlats->fetchAll(PDO::FETCH_ASSOC);


    foreach ($platsMenu as $plat) {
        if ($plat['type_plat'] === 'entrée') {
            $entrees[] = $plat;
        } elseif ($plat['type_plat'] === 'plat') {
            $plats[] = $plat;
        } elseif ($plat['type_plat'] === 'dessert') {
            $desserts[] = $plat;
        }
    }

    // ALLERGENES DU MENU //

    $stmtAllergenes = $pdo->prepare(
        'SELECT  DISTINCT allergene.libelle
         FROM menu_plat
         JOIN plat_allergene ON menu_plat.plat_id = plat_allergene.plat_id
         JOIN allergene ON plat_allergene.allergene_id = allergene.id
         WHERE menu_plat.menu_id = ?'
    );

    $stmtAllergenes->execute([$id]);
    $allergenes = $stmtAllergenes->fetchALL(PDO::FETCH_ASSOC);

}

?>

<main class="menu-detail">

    <?php if ($menu): ?>

        <section class="menu-detail__container">

            <a href="menus.php" class="menu-detail__back">Retour arrière</a>

            <h1 class="menu-detail__title"><?= htmlspecialchars($menu['titre']) ?></h1>

            <div class="menu-detail__content">

                <div class="menu-detail__left">
                    <div class="menu-detail__image">
                        <?php if (!empty($images)): ?>

                            <button class="slider-prev">←</button>
                            <img src="<?= htmlspecialchars($images[0]['url']) ?>"
                                alt="<?= htmlspecialchars($images[0]['alt']) ?>">
                            <button class="slider-next">→</button>
                            <div class="slider-counter"></div>

                        <?php else: ?>
                            <p>Aucune photo disponible</p>
                        <?php endif; ?>
                    </div>

                    <div class="menu-detail__composition">

                        <h2>Entrée(s) :</h2>

                        <?php if (!empty($entrees)): ?>
                            <?php foreach ($entrees as $entree): ?>
                                <p><?php echo htmlspecialchars($entree['nom']); ?></p>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Aucune entrée disponible</p>
                        <?php endif; ?>


                        <h2>Plat(s) :</h2>

                        <?php if (!empty($plats)): ?>
                            <?php foreach ($plats as $plat): ?>
                                <p><?php echo htmlspecialchars($plat['nom']); ?></p>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Aucun plat disponible</p>
                        <?php endif; ?>


                        <h2>Dessert(s) :</h2>

                        <?php if (!empty($desserts)): ?>
                            <?php foreach ($desserts as $dessert): ?>
                                <p><?php echo htmlspecialchars($dessert['nom']); ?></p>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Aucun dessert disponible</p>
                        <?php endif; ?>


                    </div>
                </div>

                <div class="menu-detail__right">
                    <div class="menu-detail__badges">
                        <span>Thème : <?= htmlspecialchars($menu['theme_nom']) ?></span>
                        <span>Régime : <?= htmlspecialchars($menu['regime_nom']) ?></span>
                    </div>

                    <details class="menu-detail__allergenes">
                        <summary>Liste d'allergènes ⮟</summary>

                        <?php if (!empty($allergenes)): ?>
                            <ul>
                                <?php foreach ($allergenes as $allergene): ?>
                                    <li><?php echo htmlspecialchars($allergene['libelle']); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>Aucun allergène lié à ce menu</p>
                        <?php endif; ?>

                    </details>

                    <div class="menu-detail__infos">
                        <p>
                            <strong>Description :</strong>
                            <?= htmlspecialchars($menu['description']) ?>
                        </p>
                    </div>

                    <div class="menu-detail__minimum">
                        <?= htmlspecialchars($menu['nombre_personne_min']) ?> Personnes minimum
                    </div>

                    <div class="menu-detail__infos">
                        <p class="conditions_style">
                            <strong>Conditions :</strong>
                            <?= htmlspecialchars($menu['condition_menu']) ?>
                        </p>

                        <p>
                            <strong>Stock disponible :</strong>
                            <?= htmlspecialchars($menu['stock_disponible']) ?>
                        </p>

                        <p>
                            <strong>Prix :</strong>
                            <?= htmlspecialchars($menu['prix_min']) ?> €
                        </p>
                    </div>

                    <a href="commande.php?menu_id=<?= urlencode($menu['id']) ?>" class="menu-detail__button">Commander</a>
                </div>

            </div>
        </section>

    <?php else: ?>

        <p>Menu introuvable</p>

    <?php endif; ?>

</main>

<script> const images = <?php echo json_encode($images) ?>;</script>
<script src="assets/js/slider.js"></script>
<?php include_once __DIR__ . '/../includes/footer.php'; ?>