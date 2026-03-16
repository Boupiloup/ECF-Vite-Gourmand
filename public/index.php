<?php require_once __DIR__ . '/../includes/header.php';
      require_once __DIR__ . '/../config/database.php'; 
      ?>

<main>

<!-- HERO / ACCUEIL : titre principal + bouton vers les menus -->
<section class="hero"> <!-- section du bandeau d'accueil avec l'image de fond -->
    <div class="hero-overlay"> <!-- conteneur pour centrer le contenu sur le bandeau -->
        <div class="hero-card"> <!-- bloc visuel (bulle) qui contient le texte et le bouton -->
            <h1>Bienvenue sur Vite & Gourmand</h1>
            <h2>Traiteur pour tous vos événements</h2>
            <a class="btn-primary" href="menu.php">Voir les menus</a>
        </div>
    </div>
</section>

<!-- PRESENTATION ENTREPRISE : description de l'activité -->
<section class="about">

    <h2>Présentation de l'entreprise</h2>
    <div class="about_container">

        <img class="img_about" src="assets/img/image/plat/cuisine.png"
            alt="Présentation des plats en cuisine : assiettes accompagnées de bœuf et de légumes de saison">

        <div class="text_about">

            <p>
                Vite & Gourmand est une entreprise de traiteur basée à Bordeaux, spécialisée dans la préparation de
                menus
                pour tous types d'événements : mariages, anniversaires, réceptions professionnelles ou repas familiaux.
            </p>

            <p>
                Forte de plus de 25 ans d'expérience, notre équipe met son savoir-faire culinaire au service de
                prestations
                sur mesure, en privilégiant des produits frais, de saison et de qualité.
            </p>

            <p>
                Notre objectif est d'offrir une expérience gourmande, conviviale et fiable, tout en s'adaptant aux
                besoins
                et aux contraintes de chaque client.
            </p>

        </div>

    </div>

</section>

<!-- EQUIPE : présentation des professionnels -->
<section class="team">
    <h2>Une équipe de professionnels à votre service</h2>

    <div class="team-grid">
        <article class="team-card">
            <img src="assets/img/image/icone/25ans-expérience.png"
                alt="icone d'un petit calendrier avec écrit le chiffre 25">
            <h3>25 ans d'expérience</h3>
            <p>Depuis plus de 25 ans, notre équipe accompagne particuliers et professionnels
                dans l'organisation de leurs événements. Cette expérience nous permet
                de proposer des prestations fiables, adaptées à chaque occasion
                et toujours réalisées avec le plus grand soin.</p>
        </article>

        <article class="team-card">
            <img src="assets/img/image/icone/Savoir-faire culinaire.png"
                alt="icone d'un petit calendrier avec écrit le chiffre 25">
            <h3>Savoir-faire culinaire</h3>
            <p>Notre savoir-faire culinaire repose sur une cuisine authentique,
                préparée avec des produits frais et de saison. Chaque menu est pensé
                pour offrir un équilibre de saveurs et une présentation soignée,
                afin de garantir une expérience gourmande à vos invités.</p>
        </article>


        <article class="team-card">
            <img src="assets/img/image/icone/Qualité-&-fiabilité.png"
                alt="icone d'un petit calendrier avec écrit le chiffre 25">
            <h3>Qualité & fiabilité</h3>
            <p>Nous accordons une grande importance à la qualité de nos produits
                et à la fiabilité de nos services. Notre équipe s'engage à respecter
                vos attentes, les délais et les exigences de votre événement
                afin de vous offrir une prestation professionnelle et sereine.</p>
        </article>
    </div>

</section>

<!-- AVIS CLIENTS -->
<section class="reviews">
    <h2>Avis clients</h2>

    <div class="review-grid">

        <article class="review-card">
            <div class="review-header">
                <img src="assets/img/image/photo-de-profil/homme_1.jpg" alt="Photo de profil de Robert Des Bois">

                <div class="review-meta">
                    <div class="review-stars">★★★★★</div>
                    <h3>Robert Des Bois</h3>
                </div>
            </div>

            <p>
                Service impeccable du début à la fin. Les plats étaient délicieux et très bien présentés.
                Tous les invités ont été ravis, je recommande sans hésitation.
            </p>
        </article>

        <article class="review-card">
            <div class="review-header">
                <img src="assets/img/image/photo-de-profil/femme_1.jpg" alt="Photo de profil de Jeanne Dupont">

                <div class="review-meta">
                    <div class="review-stars">★★★★★</div>
                    <h3>Jeanne Dupont</h3>
                </div>
            </div>

            <p>
                Vraiment parfait, somptueux service, je recommande totalement.
            </p>
        </article>

        <article class="review-card">
            <div class="review-header">
                <img src="assets/img/image/photo-de-profil/homme_2.jpg" alt="Photo de profil de Timothée Petit">

                <div class="review-meta">
                    <div class="review-stars">★★★★★</div>
                    <h3>Timothée Petit</h3>
                </div>
            </div>

            <p>
                Une prestation vraiment professionnelle. L'organisation était parfaite et la qualité des produits
                remarquable. Nous referons appel à eux pour un prochain événement.
            </p>
        </article>

    </div>
</section>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
