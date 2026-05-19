<?php
session_start();

$pageTitle = 'Mentions légales';

require_once __DIR__ . '/../includes/header.php';
?>

<main class="mentions-legales">

    <h1>Mentions légales</h1>

    <section>
        <h2>Éditeur du site</h2>
        <p>
            Le site Vite & Gourmand a été conçu et développé par William
            dans le cadre de l’épreuve ECF du titre professionnel
            Développeur Web et Web Mobile.
        </p>
    </section>

    <section>
        <h2>Présentation du site</h2>
        <p>
            Vite & Gourmand est un site de restauration événementielle permettant
            aux visiteurs de consulter les menus proposés, créer un compte,
            passer commande, suivre leurs commandes, contacter l’entreprise
            et laisser un avis après une commande terminée.
        </p>
    </section>

    <section>
        <h2>Responsable de publication</h2>
        <p>
            Le responsable de publication est l’administrateur du site Vite & Gourmand.
        </p>
    </section>

    <section>
        <h2>Contact</h2>
        <p>
            Les utilisateurs peuvent contacter Vite & Gourmand via le formulaire
            de contact disponible sur le site.
        </p>
    </section>

    <section>
        <h2>Hébergement</h2>
        <p>
            Le site est hébergé sur la plateforme Heroku, service de déploiement cloud
            utilisé pour la mise en ligne de l’application.
        </p>
    </section>

    <section>
        <h2>Données personnelles</h2>
        <p>
            Les données personnelles collectées sur le site sont utilisées uniquement
            pour la création de compte, la gestion des commandes, le suivi client,
            l’envoi d’emails liés au compte ou aux commandes, et le formulaire de contact.
        </p>

        <p>
            Les données concernées peuvent être le nom, le prénom, l’adresse email,
            le numéro de téléphone, l’adresse postale et les informations liées aux commandes.
        </p>
    </section>

    <section>
        <h2>Propriété intellectuelle</h2>
        <p>
            Les textes, visuels, interfaces, maquettes et éléments graphiques présents
            sur le site sont utilisés dans le cadre du projet d’évaluation ECF.
        </p>
    </section>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>