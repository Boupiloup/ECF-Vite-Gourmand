<?php 
session_start();

require_once __DIR__ . '/../includes/db.php';

$pageTitle = 'Tableau de bord employé';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit();
}

$role = (int) $_SESSION['utilisateur_role'];

if ($role !== 2 && $role !== 1) {
    header('Location: connexion.php');
    exit();
}

require_once __DIR__ . '/../includes/header.php';
?>

<main class="employe-dashboard">
    <h1>Tableau de bord employé</h1>

    <section>
        <h2>Gestion des commandes</h2>

        <p>
            Consultez les commandes clients, filtrez par statut ou par client,
            puis mettez à jour leur avancement.
        </p>

        <a href="employe-commandes.php" class="btn">
            Voir les commandes
        </a>
    </section>

    <section>
        <h2>Gestion du contenu</h2>

        <ul>
            <li><a href="gestion-menus.php">Gérer les menus</a></li>
            <li><a href="gestion-plats.php">Gérer les plats</a></li>
            <li><a href="gestion-horaires.php">Gérer les horaires</a></li>
            <li><a href="employe-gerer-avis.php">Gérer les avis clients</a></li>
        </ul>
    </section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>