<?php

$pageTitle = 'Nos menus';
include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../config/database.php';

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
        <form class="filter-form" method="GET">

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
                    <option value="noel">Noël</option>
                    <option value="paques">Pâques</option>
                    <option value="classique">Classique</option>
                    <option value="evenement">Événement</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="regime_alimentaire" class="visually-hidden">Régime alimentaire</label>
                <select id="regime_alimentaire" name="regime_alimentaire">
                    <option value="">Régime alimentaire</option>
                    <option value="classique">Classique</option>
                    <option value="vegetarien">Végétarien</option>
                    <option value="vegan">Vegan</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="nombre_personnes" class="visually-hidden">Nombre de personnes</label>
                <input type="number" id="nombre_personnes" name="nombre_personnes" placeholder="Nombre de personnes" min="0">
            </div>

        </form>
    </section>

    <!-- CARDS MENUS -->

<section class="menus-container">

    <div class="menus-grid">

        <article class="card-menu">
            <img src="assets/img/image/plat/plat1.jpg"
                alt="Plat de saumon dans une assiette accompagné de légumes">

            <h3>Saumon et salade de légumes</h3>

            <p>Salade de saumon accompagnée de légumes de saison, parfaite pour un mariage.</p>

            <div class="card-bottom">
                <p>15 personnes minimum</p>
                <p>Prix : 250 €</p>
                <button class="btn-commander">Voir le menu</button>
            </div>
        </article>

        <article class="card-menu">
            <img src="assets/img/image/plat/plat2.png"
                alt="Tartare de légumes rouges présenté dans une assiette">

            <h3>Tartare de légumes rouges</h3>

            <p>Préparation à base de légumes rouges finement découpés, mélangés à des herbes fraîches et dressés avec soin.</p>

            <div class="card-bottom">
                <p>15 personnes minimum</p>
                <p>Prix : 250 €</p>
                <button class="btn-commander">Voir le menu</button>
            </div>
        </article>

        <article class="card-menu">
            <img src="assets/img/image/plat/plat9.png"
                alt="Dinde de Noël accompagnée de fruits rôtis">

            <h3>Dinde de Noël</h3>

            <p>Dinde rôtie accompagnée de fruits rôtis, idéale pour un repas festif.</p>

            <div class="card-bottom">
                <p>15 personnes minimum</p>
                <p>Prix : 250 €</p>
                <button class="btn-commander">Voir le menu</button>
            </div>
        </article>

        <article class="card-menu">
            <img src="assets/img/image/plat/plat4.jpg"
                alt="Pavé de saumon accompagné de légumes de saison">

            <h3>Pavé de saumon</h3>

            <p>Pavé de saumon accompagné de légumes de saison, parfait pour un repas convivial.</p>

            <div class="card-bottom">
                <p>7 personnes minimum</p>
                <p>Prix : 100 €</p>
                <button class="btn-commander">Voir le menu</button>
            </div>
        </article>

        <article class="card-menu">
            <img src="assets/img/image/plat/plat5.jpg"
                alt="Poisson blanc accompagné de jeunes pousses fraîches">

            <h3>Poisson blanc et jeunes pousses</h3>

            <p>Poisson blanc délicatement préparé, accompagné de jeunes pousses fraîches et d'un assaisonnement léger.</p>

            <div class="card-bottom">
                <p>35 personnes minimum</p>
                <p>Prix : 650 €</p>
                <button class="btn-commander">Voir le menu</button>
            </div>
        </article>

        <article class="card-menu">
            <img src="assets/img/image/plat/plat8.png"
                alt="Salade de légumes de saison">

            <h3>Salade de légumes</h3>

            <p>Salade de légumes de saison, fraîche et colorée, idéale pour un événement convivial.</p>

            <div class="card-bottom">
                <p>10 personnes minimum</p>
                <p>Prix : 130 €</p>
                <button class="btn-commander">Voir le menu</button>
            </div>
        </article>

    </div>

</section>

</main>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>