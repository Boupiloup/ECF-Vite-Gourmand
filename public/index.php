<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/db.php';

/* Récupération des avis validés */
$sql = "
    SELECT
        utilisateur.prenom,
        utilisateur.nom,
        avis.note,
        avis.commentaire,
        avis.date_avis
    FROM avis
    JOIN utilisateur ON avis.utilisateur_id = utilisateur.id
    WHERE avis.valide = 1
    ORDER BY avis.date_avis DESC
    LIMIT 6
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$avisClients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main>

    <!-- HERO / ACCUEIL -->
    <section class="hero">
        <div class="hero-overlay">
            <div class="hero-card">
                <h1>Bienvenue sur Vite & Gourmand</h1>
                <h2>Traiteur pour tous vos événements</h2>
                <a class="btn-primary" href="menus.php">Voir les menus</a>
            </div>
        </div>
    </section>

    <!-- PRESENTATION ENTREPRISE -->
    <section class="about">

        <h2>Présentation de l'entreprise</h2>

        <div class="about_container">

            <img
                class="img_about"
                src="assets/img/image/plat/cuisine.png"
                alt="Présentation des plats en cuisine : assiettes accompagnées de bœuf et de légumes de saison"
            >

            <div class="text_about">
                <p>
                    Vite & Gourmand est une entreprise de traiteur basée à Bordeaux,
                    spécialisée dans la préparation de menus pour tous types d'événements :
                    mariages, anniversaires, réceptions professionnelles ou repas familiaux.
                </p>

                <p>
                    Forte de plus de 25 ans d'expérience, notre équipe met son savoir-faire
                    culinaire au service de prestations sur mesure, en privilégiant des
                    produits frais, de saison et de qualité.
                </p>

                <p>
                    Notre objectif est d'offrir une expérience gourmande,
                    conviviale et fiable.
                </p>
            </div>

        </div>

    </section>

    <!-- EQUIPE -->
    <section class="team">

        <h2>Une équipe de professionnels à votre service</h2>

        <div class="team-grid">

            <article class="team-card">
                <img
                    src="assets/img/image/icone/25ans-expérience.png"
                    alt="Icône représentant 25 ans d'expérience"
                >

                <h3>25 ans d'expérience</h3>

                <p>
                    Depuis plus de 25 ans, notre équipe accompagne
                    particuliers et professionnels.
                </p>
            </article>

            <article class="team-card">
                <img
                    src="assets/img/image/icone/Savoir-faire culinaire.png"
                    alt="Icône représentant le savoir-faire culinaire"
                >

                <h3>Savoir-faire culinaire</h3>

                <p>
                    Une cuisine authentique,
                    des produits frais
                    et des prestations de qualité.
                </p>
            </article>

            <article class="team-card">
                <img
                    src="assets/img/image/icone/Qualité-&-fiabilité.png"
                    alt="Icône représentant la qualité et la fiabilité"
                >

                <h3>Qualité & fiabilité</h3>

                <p>
                    Nous garantissons des prestations fiables,
                    professionnelles et adaptées.
                </p>
            </article>

        </div>

    </section>

    <!-- AVIS CLIENTS -->
    <section class="reviews">

        <h2>Avis clients</h2>

        <div class="review-grid">

            <?php if (empty($avisClients)) : ?>

                <p>Aucun avis client validé pour le moment.</p>

            <?php else : ?>

                <?php foreach ($avisClients as $avis) : ?>

                    <article class="review-card">

                        <div class="review-header">

                            <img
                                src="assets/img/image/photo-de-profil/homme-1.jpg"
                                alt="Photo de profil de <?= htmlspecialchars($avis['prenom'] . ' ' . $avis['nom']) ?>"
                            >

                            <div class="review-meta">

                                <div class="review-stars">
                                    <?= str_repeat('★', (int) $avis['note']) ?>
                                </div>

                                <h3>
                                    <?= htmlspecialchars($avis['prenom'] . ' ' . $avis['nom']) ?>
                                </h3>

                            </div>

                        </div>

                        <p>
                            <?= htmlspecialchars($avis['commentaire']) ?>
                        </p>

                    </article>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

    </section>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
