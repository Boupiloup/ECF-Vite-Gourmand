<?php
$pageTitle = "Mon espace";

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

if ($_SESSION['utilisateur_role'] != 3) {
    header('Location: index.php');
    exit;
}
?>

<main class="user-dashboard-page">

    <section class="user-dashboard-container">

        <h1 class="user-dashboard-title">Mon espace utilisateur</h1>

        <p class="user-dashboard-intro">
            Bienvenue dans votre espace personnel. Vous pouvez gérer vos commandes, modifier vos informations et suivre vos prestations.
        </p>

        <div class="dashboard-card-container">

            <a href="mes-commandes.php" class="dashboard-card">
                <h2>Mes commandes</h2>
                <p>Consulter l'historique et le détail de vos commandes.</p>
            </a>

            <a href="modifier-profil.php" class="dashboard-card">
                <h2>Modifier mon profil</h2>
                <p>Mettre à jour vos informations personnelles.</p>
            </a>

            <a href="suivi-commande.php" class="dashboard-card">
                <h2>Suivi de commande</h2>
                <p>Suivre l'état d'avancement de vos commandes.</p>
            </a>

            <a href="mes-avis.php" class="dashboard-card">
                <h2>Mes avis</h2>
                <p>Laisser une note et un commentaire après une commande terminée.</p>
            </a>

        </div>

    </section>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>