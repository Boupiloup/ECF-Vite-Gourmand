<?php 
session_start();

$pageTitle = "Admin Dashboard";

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: connexion.php");
    exit();
}

$role = $_SESSION['utilisateur_role'];

if ($role !== 1) {
    header('Location: connexion.php');
    exit();
}

require_once __DIR__ . '/../includes/header.php';
?> 

<main class="admin-dashboard">

    <h1>Espace administrateur</h1>

    <section class="admin-dashboard-section">
        <h2>Fonctionnalités employé</h2>

        <div class="admin-dashboard-liens">
            <a href="employe-commandes.php" class="admin-dashboard-lien">Gestion des commandes</a>
            <a href="gestion-menus.php" class="admin-dashboard-lien">Gestion des menus</a>
            <a href="gestion-plats.php" class="admin-dashboard-lien">Gestion des plats</a>
            <a href="gestion-horaires.php" class="admin-dashboard-lien">Gestion des horaires</a>
            <a href="employe-gerer-avis.php" class="admin-dashboard-lien">Gestion des avis clients</a>
        </div>
    </section>

    <section class="admin-dashboard-section">
        <h2>Fonctionnalités administrateur</h2>

        <div class="admin-dashboard-liens">
        <a href="gestion-employes.php" class="admin-dashboard-lien">Gestion des employés</a>
        <a href="admin-stats.php" class="admin-dashboard-lien">Statistiques commandes</a>
        <a href="admin-ca.php" class="admin-dashboard-lien">Chiffre d’affaires</a>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
