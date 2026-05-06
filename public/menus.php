<?php

$pageTitle = 'Nos menus';
include_once __DIR__ . '/../includes/header.php';
require_once '../includes/db.php';

$menus = $pdo->query('SELECT * FROM menu')->fetchAll(PDO::FETCH_ASSOC);

$themes = $pdo->query('SELECT * FROM theme')->fetchAll(PDO::FETCH_ASSOC);

$regimes = $pdo->query('SELECT * FROM regime')->fetchAll(PDO::FETCH_ASSOC);
?>

<main>

    <!-- HERO MENUS -->

    <section class="menus-hero">
        <div class="menus-hero-content">
            <h1>Nos menus</h1>
            <h2>Découvrez nos formules pour tous vos événements</h2>
        </div>
    </section>

    <!-- FILTRES MENUS -->

    <section class="filtre-container">
        <form id="filterForm" class="filter-form" method="GET">

            <div class="filter-group">
                <label for="prix_max" class="visually-hidden">Prix maximum</label>
                <input type="number" id="prix_max" name="prix_max" placeholder="Prix maximum" min="0">
            </div>

            <div class="filter-group">
                <label for="fourchette_prix" class="visually-hidden">Fourchette de prix</label>
                <select id="fourchette_prix" name="fourchette_prix">
                    <option value="">Fourchette de prix</option>
                    <option value="0-200">0 - 200 €</option>
                    <option value="200-500">200 - 500 €</option>
                    <option value="500-800">500 - 800 €</option>
                    <option value="800-1100">800 - 1100 €</option>
                    <option value="1100-2000">1100 - 2000 €</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="theme" class="visually-hidden">Thème</label>
                <select id="theme" name="theme">
                    <option value="">Thème</option>
                    <?php foreach ($themes as $theme): ?>
                        <option value="<?php echo htmlspecialchars($theme['id']); ?>">
                            <?php echo htmlspecialchars($theme['libelle']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="regime_alimentaire" class="visually-hidden">Régime alimentaire</label>
                <select id="regime_alimentaire" name="regime_alimentaire">
                    <option value="">Régime alimentaire</option>
                    <?php foreach ($regimes as $regime): ?>
                        <option value="<?php echo htmlspecialchars($regime['id']); ?>">
                            <?php echo htmlspecialchars($regime['libelle']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="nombre_personnes" class="visually-hidden">Nombre de personnes</label>
                <input type="number" id="nombre_personnes" name="nombre_personnes" placeholder="Nombre de personnes"
                    min="0">
            </div>

        </form>
    </section>

    <!-- CARDS MENUS -->

    <section class="menus-container">

        <div id="menusGrid" class="menus-grid">

            <?php
            foreach ($menus as $menu): ?>

                <?php $images = $pdo->prepare('SELECT * FROM image where menu_id =? ORDER BY id ASC');
                $images->execute([$menu['id']]);
                $image = $images->fetch(PDO::FETCH_ASSOC); ?>

                <article class="card-menu">
                    <img src="<?php echo htmlspecialchars($image['url'] ?? 'https://picsum.photos/seed/menu2-1/800/600'); ?>"
                        alt="<?php echo htmlspecialchars($image['alt'] ?? 'Image du menu'); ?>">
                    <h3><?php echo htmlspecialchars($menu['titre']); ?></h3>

                    <p><?php echo htmlspecialchars($menu['description']); ?></p>

                    <div class="card-bottom">
                        <p><?php echo htmlspecialchars($menu['nombre_personne_min']); ?> personnes minimum</p>
                        <p>Prix : <?php echo htmlspecialchars($menu['prix_min']); ?> €</p>
                        <a href="menu_vuedetail.php?id=<?php echo htmlspecialchars($menu['id']); ?>"
                            class="btn-commander">Voir le menu</a>
                    </div>
                </article>

            <?php endforeach; ?>
        </div>

    </section>

</main>

<script src="assets/js/filtresMenus.js"></script>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>